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

    // Guarda dados da sessão
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
   
    $app_id = trim($info['APP ID'] ?? $info['app_id'] ?? '');
    $security_key = trim($info['Security Key ']);

    if (!$app_id || !$security_key) {
        \Log::error('PagSmile - credenciais ausentes', ['info' => $info]);
        return redirect()->back()->with('error', 'Configuração PagSmile inválida.')->withInput();
    }

    // Gera order e unique id
    $txnId = UserPermissionHelper::uniqidReal(8);
    $order = Common::saveOrder($request->all(), $txnId, null, 'Pending', 'online', $user->id);
    $order_id = $order->id;
    Common::saveOrderedItems($order->id);

    $uniqueOrderId = $order_id . '-' . time();
    $order->unique_payment_id = $uniqueOrderId;
    $order->save();

    // Timestamp conforme doc
    $timestamp = now()->format('Y-m-d H:i:s');
  $notifyUrl = route('customer.itemcheckout.pagSmile.notify', getParam());
    \Log::info('PagSmile notify_url gerada:', ['notify_url' => $notifyUrl]);
    // Monta payload conforme exemplo da doc (observe nomes: charset, app_id, out_trade_no, content, customer..., etc)
    $payload = [
        'charset'         => 'UTF-8',
        'app_id'          => $app_id,
        'out_trade_no'    => $uniqueOrderId,
        'order_currency'  => 'BRL',
        'order_amount'    => number_format($amount, 2, '.', ''),
        'subject'         => $title,
        'content'         => $description,              // ATT: usa "content" na doc, não "body"
        'trade_type'      => 'WEB',
        'timeout_express' => '90m',
        'timestamp'       => $timestamp,
        'notify_url'      => $notifyUrl,
        'buyer_id'        => $email,
        'version'         => '2.0',
        // customer é um objeto conforme doc
        'customer' => [
            'identify' => [
                'type'   => $request->identify_type ?? null, // opcional
                'number' => $request->identify_number ?? null, // opcional
            ],
            'name'  => $request->name ?? 'Cliente',
            'email' => $email,
        ],
        // opcional: regions, address
        // 'regions' => ['BRA'],
        // 'address' => ['zip_code' => '38082365'],
    ];

    // Remove chaves com null (se não tiver identify)
    if (empty($payload['customer']['identify']['type']) && empty($payload['customer']['identify']['number'])) {
        unset($payload['customer']['identify']);
    }

    // Monta Authorization header (Basic base64(app_id:security_key))
    $authorization = 'Basic ' . base64_encode("{$app_id}:{$security_key}");

    // Chamada: use gateway-test.pagsmile.com para sandbox, gateway.pagsmile.com para produção
    $endpoint = 'https://gateway.pagsmile.com/trade/create'; // ajustar conforme ambiente

    // Envia request
    $response = Http::withHeaders([
        'Content-Type'  => 'application/json',
        'Authorization' => $authorization,
    ])->post($endpoint, $payload);

    // DEBUG: veja o payload enviado e a resposta (remova dd() em produção)
   

    // --- Depois de inspecionar com dd(), comente o dd() e trate a resposta ---
    if ($response->successful()) {
       $data = $response->json();
       // A doc retorna prepay_id ou web_url; verifique o campo retornado
       if (isset($data['prepay_id'])) {
           $prepay = $data['prepay_id'];
           $checkoutUrl = "http://checkout.pagsmile.com?prepay_id={$prepay}";
           // anexar return_url opcional:
           if (!empty($successUrl)) {
               $checkoutUrl .= '&return_url=' . urlencode($successUrl);
           }
           Session::forget('cart');
           Session::forget('user_request');
           return redirect()->away($checkoutUrl);
       }
       if (isset($data['web_url'])) {
           Session::forget('cart');
           Session::forget('user_request');
           return redirect()->away($data['web_url']);
       }
       \Log::error('PagSmile - URL de checkout não encontrada:', ['response' => $data, 'payload' => $payload, 'order_id' => $order_id]);
       return redirect()->back()->with('error', 'Erro: URL de checkout não encontrada.')->withInput();
    }
    
            // Envia email de atualização do pedido ao cliente
            try {
                $user = $order->user ?? User::find($order->user_id);
                // Busca dados do cliente
                $customer = $order->customer_id ? \App\Models\Customer::find($order->customer_id) : null;
                if ($customer) {
                    $f_name = $customer->first_name;
                    $l_name = $customer->last_name;
                    $email = $customer->email;
                } else {
                    $f_name = $order->billing_fname;
                    $l_name = $order->billing_lname;
                    $email = $order->billing_email;
                }

                // Remove invoice anterior e gera nova
                @unlink(public_path('assets/front/invoices/') . $order->invoice_number);
                $invoice = Common::generateInvoice($order, $user);
                $order->update(['invoice_number' => $invoice]);

                // Busca template de email
                $mail_template = \App\Models\User\UserEmailTemplate::where([
                    ['user_id', $user->id],
                    ['email_type', 'product_order_status']
                ])->first();
                if ($mail_template) {
                    $mail_subject = $mail_template->email_subject;
                    $mail_body = $mail_template->email_body;

                    $mail_body = str_replace('{customer_name}', $f_name . ' ' . $l_name, $mail_body);
                    $mail_body = str_replace('{order_status}', $order->payment_status, $mail_body);
                    $mail_body = str_replace('{website_title}', $user->shop_name ?? $user->username, $mail_body);

                    $to = $email;
                    $data = [];
                    $data['recipient'] = $to;
                    $data['subject'] = $mail_subject;
                    $data['body'] = $mail_body;
                    $data['invoice'] = public_path('assets/front/invoices/' . $order->invoice_number);
                    \App\Http\Helpers\BasicMailer::sendMailFromUser($user, $data);
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email de atualização do pedido PagSmile', ['error' => $e->getMessage()]);
            }
    \Log::error('PagSmile - Erro na comunicação:', ['status_code' => $response->status(), 'response' => $response->json(), 'payload' => $payload, 'order_id' => $order_id]);
    return redirect()->back()->with('error', 'Erro ao comunicar com o gateway PagSmile.')->withInput();
}


    public function successPayment(Request $request)
    {
        return redirect()->route('customer.success.page', getParam());
    }
    public function notifyPayment(Request $request)
    {
        $payload = $request->all();

        $uniqueOrderId = $payload['out_trade_no'] ?? null;
        $status = $payload['trade_status'] ?? null;
        $transactionId = $payload['trade_no'] ?? null;

        if (!$uniqueOrderId || !$status) {
            \Log::warning('Webhook PagSmile com dados incompletos.', $payload);
            return response('success', 200); // Mesmo se estiver inválido, responde "success"
        }

        $order = UserOrder::where('unique_payment_id', $uniqueOrderId)->first();

        if (!$order) {
            \Log::warning("Pedido não encontrado para out_trade_no: {$uniqueOrderId}");
            return response('success', 200);
        }

        // Mapeamento dos status possíveis para seu sistema
        switch ($status) {
            case 'SUCCESS':
                $order->payment_status = 'Completed';
                // Atualiza status do pedido para 'aprovado' (ou 'concluido' se preferir)
                $aprovadoStatus = \App\Models\User\OrderStatus::where('code', 'aprovado')->first();
                if ($aprovadoStatus) {
                    $order->order_status_id = $aprovadoStatus->id;
                    $order->order_status = $aprovadoStatus->code;
                }
                break;

            case 'CANCEL':
            case 'EXPIRED':
            case 'CHARGEBACK':
            case 'CHARGEBACK_REVERSED':
            case 'REFUNDED':
            case 'REFUND_REFUSED':
            case 'REFUND_REVOKE':
                $order->payment_status = 'Cancelled';
                $canceladoStatus = \App\Models\User\OrderStatus::where('code', 'cancelado')->first();
                if ($canceladoStatus) {
                    $order->order_status_id = $canceladoStatus->id;
                    $order->order_status = $canceladoStatus->code;
                }
                break;

            case 'REFUSED':
            case 'REFUSE_FAILED':
                $order->payment_status = 'Rejected';
                $rejeitadoStatus = \App\Models\User\OrderStatus::where('code', 'cancelado')->first();
                if ($rejeitadoStatus) {
                    $order->order_status_id = $rejeitadoStatus->id;
                    $order->order_status = $rejeitadoStatus->code;
                }
                break;

            case 'DISPUTE':
                $order->payment_status = 'Disputed';
                break;

            case 'PROCESSING':
            case 'RISK_CONTROLLING':
            case 'REFUND_VERIFYING':
            case 'REFUND_PROCESSING':
                $order->payment_status = 'Pending';
                $pendenteStatus = \App\Models\User\OrderStatus::where('code', 'pending')->first();
                if ($pendenteStatus) {
                    $order->order_status_id = $pendenteStatus->id;
                    $order->order_status = $pendenteStatus->code;
                }
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
