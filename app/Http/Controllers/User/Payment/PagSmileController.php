<?php

namespace App\Http\Controllers\User\Payment;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User;
use App\Models\User\UserOrder;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Session;

class PagSmileController extends Controller
{
    public function paymentProcess(Request $request, $amount, $email, $successUrl, $cancelUrl, $title, $description)
    {
        $user = getUser();

        // Armazena os dados na sessão para recuperar após o pagamento
        Session::put('user_request', $request->all());
        Session::put('user_success_url', $successUrl);
        Session::put('user_cancel_url', $cancelUrl);

        $config = UserPaymentGeteway::where([
            ['user_id', $user->id],
            ['keyword', 'pagsmile']
        ])->first();

        if (!$config) {
            return response()->json(['error' => 'Configuração PagSmile não encontrada.'], 400);
        }

        $info = json_decode($config->information, true);
        $app_id = $info['APP ID'];
        $security_key = trim($info['Security Key ']); // remove espaços se tiver

        $authorization = 'Basic ' . base64_encode("{$app_id}:{$security_key}");
        $timestamp = now()->format('Y-m-d H:i:s');

        // Salva pedido com status pendente
        $txnId = UserPermissionHelper::uniqidReal(8);
        $chargeId = null; // ainda não temos o ID do gateway
        $order = Common::saveOrder($request->all(), $txnId, $chargeId, 'Pending', 'online', $user->id);
        $order_id = $order->id;
        Common::saveOrderedItems($order->id);

        // Payload para PagSmile
        $payload = [
            'app_id'            => $app_id,
            'out_trade_no'      => $order_id,
            'timestamp'         => $timestamp,
            // 'notify_url'        => route('customer.itemcheckout.pagSmile.notify', ['domain' => request()->route('domain')]),
            'notify_url'        => 'https://lightgrey-horse-872687.hostingersite.com/receber.php',
            'subject'           => $title,
            'body'              => $description,
            'order_amount'      => number_format($amount, 2, '.', ''),
            'order_currency'    => 'BRL',
            'trade_type'        => 'WEB',
            'return_url'        => $successUrl,
            'cancel_url'        => $cancelUrl,
            'version'           => '2.0',
            'buyer_id'          => $email,
            'customer.email'    => $email,
            'customer.name'     => $request->name ?? 'Cliente',
            'timeout_express'   => '90m'
        ];

        // Requisição ao gateway PagSmile
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json; charset=UTF-8',
            'Authorization' => $authorization,
        ])->post('https://gateway-test.pagsmile.com/trade/create', $payload);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['web_url'])) {
                Session::forget('cart');
                Session::forget('user_request');
                Session::forget('user_amount');
                return redirect()->away($data['web_url']);
            }

            return redirect()->back()->with('error', 'Erro: URL de checkout não encontrada.')->withInput();
        }

        return redirect()->back()->with('error', 'Erro ao comunicar com o gateway PagSmile.')->withInput();
    }

    public function successPayment(Request $request)
    {
        return redirect()->route('customer.success.page', getParam());
    }
    public function notifyPayment(Request $request)
    {
        $payload = $request->all();
        \Log::info('Webhook PagSmile recebido:', $payload);

        $orderId = $payload['out_trade_no'] ?? null;
        $status = $payload['trade_status'] ?? null;
        $transactionId = $payload['trade_no'] ?? null;

        if (!$orderId || !$status) {
            \Log::warning('Webhook PagSmile com dados incompletos.', $payload);
            return response('success', 200); // Mesmo se estiver inválido, responde "success"
        }

        $order = UserOrder::where('id', $orderId)->first();

        if (!$order) {
            \Log::warning("Pedido não encontrado para out_trade_no: {$orderId}");
            return response('success', 200);
        }

        // Mapeamento dos status possíveis para seu sistema
        switch ($status) {
            case 'SUCCESS':
                $order->payment_status = 'Completed';
                break;

            case 'CANCEL':
            case 'EXPIRED':
            case 'CHARGEBACK':
            case 'CHARGEBACK_REVERSED':
            case 'REFUNDED':
            case 'REFUND_REFUSED':
            case 'REFUND_REVOKE':
                $order->payment_status = 'Cancelled';
                break;

            case 'REFUSED':
            case 'REFUSE_FAILED':
                $order->payment_status = 'Rejected';
                break;

            case 'DISPUTE':
                $order->payment_status = 'Disputed';
                break;

            case 'PROCESSING':
            case 'RISK_CONTROLLING':
            case 'REFUND_VERIFYING':
            case 'REFUND_PROCESSING':
                $order->payment_status = 'Pending';
                break;

            default:
                \Log::info("Status desconhecido recebido: {$status}");
                $order->payment_status = 'Unknown';
                break;
        }

        // Atualiza ID da transação do Pagsmile (se quiser armazenar)
        $order->charge_id = $transactionId;
        $order->save();

        // Lógica adicional apenas para sucesso
        if ($status == 'SUCCESS') {
            $user = $order->user ?? User::find($order->user_id);
            Common::generateInvoice($order, $user);
            Common::OrderCompletedMail($order, $user);
        }

        return response('success', 200);
    }

    public function cancelPayment(Request $request)
    {
      return redirect()->back()->with('error', 'Payment Cancelled.');
    }
}
