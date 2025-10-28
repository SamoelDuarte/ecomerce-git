<?php

namespace App\Http\Helpers;

use PDF;
use App\Models\Customer;
use App\Models\Language;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserOrder;
use App\Models\User\UserShippingCharge;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User\Language as UserLanguage;
use App\Models\User\ProductVariantOption;
use App\Models\User\UserItemContent;
use App\Models\User\UserOrderItem;
use Carbon\Carbon;
use App\Models\User\BasicSetting;
use App\Models\User\DigitalProductCode;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserShopSetting;
use DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Omnipay\Common\Item;

class Common
{
    use AuthorizesRequests, ValidatesRequests;

    public static function sendMailFacadeMail($request, $file_name, $be, $subject, $body, $email, $name)
    {
        /******** Send mail to user ********/
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail info in array
        $data['from_mail'] = $be->from_mail;
        $data['recipient'] = $email;
        $data['subject'] = $subject;
        $data['body'] = $body;
        BasicMailer::sendMail($data);
        if ($file_name) {
            @unlink(public_path('assets/front/invoices/') . $file_name);
        }
    }

    public static function makeInvoice($request, $key, $member, $password, $amount, $payment_method, $phone, $base_currency_text_position, $base_currency_symbol, $base_currency_text, $order_id, $package_title, $status)
    {
        $file_name = uniqid($key) . ".pdf";
        // Monta endereço completo do usuário
        $address = ($request['address'] ?? '') . ', ' . ($request['city'] ?? '') . ', ' . ($request['district'] ?? '') . ', ' . ($request['zip'] ?? '');
        $country = 'Brasil';
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.membership', compact('request', 'member', 'password', 'amount', 'payment_method', 'phone', 'base_currency_text_position', 'base_currency_symbol', 'base_currency_text', 'order_id', 'package_title', 'status', 'address', 'country'));
        $output = $pdf->output();
        $dir = public_path('assets/front/invoices/');
        @mkdir($dir, '0775', true);
        @file_put_contents($dir . $file_name, $output);
        return $file_name;
    }

    public static function resetPasswordMail($email, $name, $subject, $body)
    {
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;
        /******** Send mail to user ********/
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail info in array
        $data['from_mail'] = $be->from_mail;
        $data['recipient'] = $email;
        $data['subject'] = $subject;
        $data['body'] = $body;
        BasicMailer::sendMail($data);
    }

    // items checkout
    public static function orderTotal($shipping, $user_id)
    {
        if ($shipping != 0) {
            $shipping = UserShippingCharge::findOrFail($shipping);
            $shippig_charge = currency_converter($shipping->charge);
        } else {
            $shippig_charge = 0;
        }

        //cartTotal
        $cartTotal =  Self::cartTotal($user_id);
        //tax
        $tax =  Self::tax($user_id);

        $total = round(($cartTotal - coupon()) + $shippig_charge + $tax, 2);

        return round($total, 2);
    }

    public static function tax($user_id)
    {

        $bex = UserShopSetting::where('user_id', $user_id)->first();
        $tax = $bex->tax;
        if (session()->has('cart') && !empty(session()->get('cart'))) {
            $cartSubTotal =  Self::cartSubTotal($user_id);
            $tax = ($cartSubTotal * $tax) / 100;
        }

        return round($tax, 2);
    }

    public static function cartSubTotal($user_id)
    {
        $coupon = session()->has('user_coupon') && !empty(session()->get('user_coupon')) ? session()->get('user_coupon') : 0;
        //cartTotal
        $cartTotal =  Self::cartTotal($user_id);
        $subTotal = $cartTotal - $coupon;

        return round($subTotal, 2);
    }
    public static function tax_percentage($user_id)
    {

        $bex = UserShopSetting::where('user_id', $user_id)->first();
        return $bex->tax;
    }
    public static function cartTotal($user_id)
    {

        $total = 0;
        if (session()->has('cart') && !empty(session()->get('cart'))) {
            $cart = session()->get('cart');

            if (!is_null($cart) && is_array($cart)) {
                $cart = array_filter($cart, function ($item) use ($user_id) {
                    return $item['user_id'] == $user_id;
                });
                foreach ($cart as $key => $cartItem) {
                    $total += $cartItem['total'];
                }
            }
        }

        return round($total, 2);
    }

    public static function orderValidation($request, $gtype = 'online', $user_id = null)
    {
        $rules = [
            'billing_fname' => 'required',
            'billing_lname' => 'required',
            'billing_number' => 'required',
            'billing_email' => 'required|email',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_zip' => 'required',
            'billing_street' => 'required',
            'billing_number_home' => 'required',
            'billing_neighborhood' => 'required',
            'billing_reference' => 'nullable|string',

            'payment_method' => 'required',

            'shipping_fname' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_lname' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_number' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_email' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_city' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_state' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_country' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_street' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_number_address' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_neighborhood' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_zip' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_reference' => $request->checkbox == 'on' ? 'nullable|string' : '',

            'identity_number' => $request->payment_method == 'Iyzico' ? 'required' : '',
        ];

        if ($gtype == 'offline') {
            $gateway = UserOfflineGateway::where([
                ['name', $request->payment_method],
                ['user_id', $user_id]
            ])->first();

            if ($gateway && $gateway->is_receipt == 1) {
                $rules['receipt'] = [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $ext = $request->file('receipt')->getClientOriginalExtension();
                        if (!in_array($ext, ['jpg', 'png', 'jpeg'])) {
                            return $fail("Apenas imagens PNG, JPG ou JPEG são permitidas.");
                        }
                    },
                ];
            }
        }

        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'email' => 'O campo :attribute deve ser um e-mail válido.',
        ];

        $attributes = [
            'billing_fname' => 'nome',
            'billing_lname' => 'sobrenome',
            'billing_number' => 'telefone',
            'billing_email' => 'e-mail',
            'billing_city' => 'cidade',
            'billing_state' => 'estado',
            'billing_zip' => 'CEP',
            'billing_street' => 'rua',
            'billing_number_home' => 'número',
            'billing_neighborhood' => 'bairro',
            'billing_reference' => 'referência',

            'payment_method' => 'método de pagamento',

            'shipping_fname' => 'nome de envio',
            'shipping_lname' => 'sobrenome de envio',
            'shipping_number' => 'telefone de envio',
            'shipping_email' => 'e-mail de envio',
            'shipping_city' => 'cidade de envio',
            'shipping_state' => 'estado de envio',
            'shipping_country' => 'país de envio',
            'shipping_street' => 'rua de envio',
            'shipping_number_address' => 'número de envio',
            'shipping_neighborhood' => 'bairro de envio',
            'shipping_zip' => 'CEP de envio',
            'shipping_reference' => 'referência de envio',

            'identity_number' => 'número de identidade',
            'receipt' => 'comprovante de pagamento',
        ];

        $request->validate($rules, $messages, $attributes);
    }


    public static function saveOrder($request, $txnId, $chargeId, $paymentStatus = 'Pending', $gtype = 'online', $user_id)
    {
        // Calcula total sem frete (usar 0 para ignorar shipping_charge antigo)
        $total = Common::orderTotal(0, $user_id);

        // Valor do frete vindo do Frenet
        $shipping_service_price = floatval($request['shipping_service_price'] ?? 0);

        // Soma o valor do frete no total
        $total += $shipping_service_price;


        $coupon_amount = session()->get('user_coupon');
        $total = $total - session()->get('user_coupon');
        
        // Dados do frete do Frenet
        $shippig_charge = $shipping_service_price;
        $shipping_method = $request['shipping_service_name'] ?? null;


        if (Session::has('myfatoorah_user')) {
            $user = Session::get('myfatoorah_user');
        } else {
            $user = getUser();
        }

        $timeZone = DB::table('user_basic_settings')->where('user_id', $user->id)->value('timezone');
        $now = Carbon::now($timeZone);

        $order_status = 'Pagamento Pendente';
        $cart = session()->get('cart', []);
        if (count($cart) == 1) {
            foreach ($cart as $itemCart)
                $itemType = UserItem::where([['user_id', $user->id], ['id', $itemCart['id']]])->pluck('type')->first();
            if ($itemType == 'digital') {
                $order_status = 'Pagamento Pendente';
            }
        }

        $order = new UserOrder();

        $order->customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : 9999999;
        $order->user_id = $user->id;

        // Billing
        $order->billing_fname = $request['billing_fname'];
        $order->billing_lname = $request['billing_lname'];
        $order->billing_email = $request['billing_email'];
        $order->billing_city = $request['billing_city'];
        $order->billing_state = $request['billing_state'];
        $order->billing_number = $request['billing_number'];
        $order->billing_street = $request['billing_street'];
        $order->billing_number_home = $request['billing_number_home'];
        $order->billing_neighborhood = $request['billing_neighborhood'];
        $order->billing_zip = $request['billing_zip'];
        $order->billing_reference = $request['billing_reference'] ?? null;

        // Shipping (usa billing como fallback)
        $order->shipping_fname = $request['shipping_fname'] ?? $request['billing_fname'];
        $order->shipping_lname = $request['shipping_lname'] ?? $request['billing_lname'];
        $order->shipping_email = $request['shipping_email'] ?? $request['billing_email'];
        $order->shipping_city = $request['shipping_city'] ?? $request['billing_city'];
        $order->shipping_state = $request['shipping_state'] ?? $request['billing_state'];
        $order->shipping_number = $request['shipping_number'] ?? $request['billing_number'];
        $order->shipping_street = $request['shipping_street'] ?? $request['billing_street'];
        $order->shipping_number_address = $request['shipping_number_address'] ?? $request['billing_number_home'];
        $order->shipping_neighborhood = $request['shipping_neighborhood'] ?? $request['billing_neighborhood'];
        $order->shipping_zip = $request['shipping_zip'] ?? $request['billing_zip'];
        $order->shipping_reference = $request['shipping_reference'] ?? $request['billing_reference'];

        // Frete
        $order->shipping_service = $request['shipping_service_name'];
        $order->shipping_price = $request['shipping_service_price'];



        $order->order_status = $order_status;
        $order->order_status_id = 1;
        $order->gateway_type = $gtype;
        if (is_array($request)) {
            if (array_key_exists('conversation_id', $request)) {
                $conversation_id = $request['conversation_id'];
            } else {
                $conversation_id = null;
            }
        } else {
            $conversation_id = null;
        }
        $order->conversation_id = $conversation_id;
        $order->cart_total = Self::cartTotal($user->id);
        $order->tax = Self::tax($user->id);
        $order->tax_percentage = Self::tax_percentage($user->id);
        $order->discount = $coupon_amount;
        $order->total = $total;
        $order->shipping_method = $shipping_method;
        $order->shipping_charge = round($shippig_charge, 2);
        if ($gtype == 'online') {
            $order->method = $request['payment_method'];
        } elseif ($gtype == 'offline') {
            $gateway =  UserOfflineGateway::where([['user_id', $user->id], ['name', $request['payment_method']]])
                ->first();
            $order->method = $gateway->name;
            if ($request->hasFile('receipt')) {
                $dir = public_path('assets/front/receipt/');
                $receipt = Uploader::upload_picture($dir, $request->file('receipt'));
                $order->receipt = $receipt;
            } else {
                $order->receipt = NULL;
            }
        }
        $CurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->first();
        $order->currency_code = $CurrentCurr->text;
        $order->currency_text_position = $CurrentCurr->text_position;
        $order->currency_sign = $CurrentCurr->symbol;
        $order->currency_position = $CurrentCurr->symbol_position;
        $order['currency_id'] = $CurrentCurr->id;
        $order['order_number'] = \Str::random(4) . time();
        $order['payment_status'] = $paymentStatus;
        $order['txnid'] = $txnId;
        $order['charge_id'] = $chargeId;
        $order->created_at = $now;
        $order->updated_at = $now;
        $order->save();

        // Atualizar o perfil do cliente com os dados do checkout
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->user()->id;
            
            $updateData = [
                // Billing
                'billing_fname' => $request['billing_fname'] ?? null,
                'billing_lname' => $request['billing_lname'] ?? null,
                'billing_email' => $request['billing_email'] ?? null,
                'billing_city' => $request['billing_city'] ?? null,
                'billing_state' => $request['billing_state'] ?? null,
                'billing_number' => $request['billing_number'] ?? null,
                'billing_zip' => $request['billing_zip'] ?? null,
                'billing_street' => $request['billing_street'] ?? null,
                'billing_number_home' => $request['billing_number_home'] ?? null,
                'billing_neighborhood' => $request['billing_neighborhood'] ?? null,
                'billing_reference' => $request['billing_reference'] ?? null,
                'billing_country' => $request['billing_country'] ?? 'BR',
            ];
            
            // Shipping (se preenchido, senão usa billing)
            if (!empty($request['shipping_fname'])) {
                $updateData = array_merge($updateData, [
                    'shipping_fname' => $request['shipping_fname'] ?? null,
                    'shipping_lname' => $request['shipping_lname'] ?? null,
                    'shipping_email' => $request['shipping_email'] ?? null,
                    'shipping_city' => $request['shipping_city'] ?? null,
                    'shipping_state' => $request['shipping_state'] ?? null,
                    'shipping_number' => $request['shipping_number'] ?? null,
                    'shipping_zip' => $request['shipping_zip'] ?? null,
                    'shipping_street' => $request['shipping_street'] ?? null,
                    'shipping_number_address' => $request['shipping_number_address'] ?? null,
                    'shipping_neighborhood' => $request['shipping_neighborhood'] ?? null,
                    'shipping_reference' => $request['shipping_reference'] ?? null,
                    'shipping_country' => $request['shipping_country'] ?? 'BR',
                ]);
            } else {
                // Usar dados de billing para shipping se não preenchido
                $updateData = array_merge($updateData, [
                    'shipping_fname' => $request['billing_fname'] ?? null,
                    'shipping_lname' => $request['billing_lname'] ?? null,
                    'shipping_email' => $request['billing_email'] ?? null,
                    'shipping_city' => $request['billing_city'] ?? null,
                    'shipping_state' => $request['billing_state'] ?? null,
                    'shipping_number' => $request['billing_number'] ?? null,
                    'shipping_zip' => $request['billing_zip'] ?? null,
                    'shipping_street' => $request['billing_street'] ?? null,
                    'shipping_number_address' => $request['billing_number_home'] ?? null,
                    'shipping_neighborhood' => $request['billing_neighborhood'] ?? null,
                    'shipping_reference' => $request['billing_reference'] ?? null,
                    'shipping_country' => $request['shipping_country'] ?? 'BR',
                ]);
            }
            
            // Atualizar o perfil do cliente
            Customer::where('id', $customerId)->update($updateData);
        }

        return $order;
    }

    public static function saveOrderedItems($orderId)
    {
        $cart = Session::get('cart');
        $items = [];
        $qty = [];
        $variations = [];
        $codesList = [];

        foreach ($cart as $id => $item) {
            $qty[] = $item['qty'];
            $itemPrincipal = UserItem::findOrFail($item['id']);

            if ($itemPrincipal->hasCode()) {
                // Buscar códigos digitais disponíveis (sem filtrar por nome)
                $codigos = DigitalProductCode::where('user_item_id', $itemPrincipal->id)
                    ->where('is_used', 0)
                    ->limit($item['qty'])
                    ->get();

                $codesArray = [];

                foreach ($codigos as $codigo) {
                    $codesArray[] = [
                        'code'  => $codigo->code
                    ];

                    // Atualiza como usado
                    $codigo->update([
                        'is_used'  => 1,
                        'order_id' => $orderId,
                        'used_at'  => now(),
                    ]);
                }

                $codesList[] = json_encode($codesArray);
                $variations[] = null; // sem variação para produto com code
            } else {
                $variations[] = json_encode($item['variations']);
                $codesList[] = null;
            }

            $items[] = $itemPrincipal;
        }

        // Busca usuário
        $user = Session::has('myfatoorah_user') ? Session::get('myfatoorah_user') : getUser();

        // Idioma do usuário
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))
                ->where('user_id', $user->id)
                ->firstOrFail();
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        foreach ($items as $key => $item) {
            $isDigital = method_exists($item, 'hasCode') && $item->hasCode();

            $itemcontent = UserItemContent::where('item_id', $item->id)
                ->where('language_id', $userCurrentLang->id)
                ->first();

            if ($isDigital && !empty($codesList[$key])) {
                // Para produtos digitais com códigos, usar o preço do produto multiplicado pela quantidade
                $codesDecoded = json_decode($codesList[$key], true);
                $codeQuantity = count($codesDecoded);
                $item_price = currency_converter(
                    ($item->flash == 1
                        ? ($item->current_price - ($item->current_price * ($item->flash_amount / 100)))
                        : $item->current_price)
                ) * $codeQuantity;
            } else {
                // Preço físico normal
                $item_price = currency_converter(
                    ($item->flash == 1
                        ? ($item->current_price - ($item->current_price * ($item->flash_amount / 100)))
                        : $item->current_price)
                );
            }

            // Atualiza estoque físico
            $orderderd_variations = json_decode($variations[$key]);
            if ($orderderd_variations) {
                foreach ($orderderd_variations as $vkey => $value) {
                    $option = ProductVariantOption::find(intval($value->option_id));
                    if ($option) {
                        $option->stock -= $qty[$key];
                        $option->save();
                    }
                }
            }

            $timeZone = DB::table('user_basic_settings')
                ->where('user_id', $user->id)
                ->value('timezone');

            UserOrderItem::insert([
                'user_order_id'   => $orderId,
                'customer_id'     => Auth::guard('customer')->check()
                    ? Auth::guard('customer')->user()->id
                    : 9999999,
                'user_id'         => $user->id,
                'item_id'         => $item->id,
                'title'           => $itemcontent->title,
                'sku'             => $item->sku,
                'qty'             => $qty[$key],
                'variations'      => $variations[$key],
                'codes'           => $codesList[$key],
                'category'        => $itemcontent->category_id,
                'price'           => $item_price,
                'previous_price'  => $item->previous_price,
                'image'           => $item->thumbnail,
                'summary'         => $itemcontent->summary ?? '',
                'description'     => $itemcontent->description ?? '',
                'created_at'      => Carbon::now($timeZone),
            ]);
        }
    }



    public static function sendMails($order)
    {
        $user = getUser();
        $data['userBs'] = BasicSetting::where('user_id', $user->id)->first();
        $fileName = \Str::random(4) . time() . '.pdf';
        $dir = public_path('assets/front/invoices/');
        $path = $dir . $fileName;
        @mkdir($dir, 0777, true);
        $data['order']  = $order;
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.item', $data)->save($path);
        UserOrder::where('id', $order->id)->update([
            'invoice_number' => $fileName
        ]);

        // Send Mail to Buyer
        $mailer = new MegaMailer();
        $data = [
            'toMail' => $order->billing_email,
            'toName' => $order->billing_fname,
            'attachment' => $fileName,
            'customer_name' => $order->billing_fname,
            'order_number' => $order->order_number,
            'order_link' => !empty($order->customer_id) ? "<strong>Order Details:</strong> <a href='" . route('customer.orders-details', ['id' => $order->id, getParam()]) . "'>" . route('customer.orders-details', ['id' => $order->id, getParam()]) . "</a>" : "",
            'website_title' => $data['userBs']->website_title,
            'templateType' => 'product_order',
            'type' => 'productOrder'
        ];
        $mailer->mailFromUser($data);
        Session::forget('cart');
        Session::forget('coupon');
    }

    public static function generateInvoice($order, $user)
    {
        $data['userBs'] = BasicSetting::where('user_id', $user->id)->first();
        $fileName = \Str::random(4) . time() . '.pdf';
        $dir = public_path('assets/front/invoices/');
        $path = $dir . $fileName;
        @mkdir($dir, 0777, true);
        // Monta endereço completo do pedido
        $address = ($order->billing_street ?? '') . ', ' . ($order->billing_number_home ?? '') . ', ' . ($order->billing_neighborhood ?? '') . ', ' . ($order->billing_zip ?? '');
        $country = 'Brasil';
        $data['order']  = $order;
        $data['address'] = $address;
        $data['country'] = $country;
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.item', $data)->save($path);
        UserOrder::where('id', $order->id)->update([
            'invoice_number' => $fileName
        ]);
        return $fileName;
    }

    public static function OrderCompletedMail($order, $user)
    {
        // first, get the mail template information from db
        $mailTemplate = UserEmailTemplate::where([['email_type', 'product_order'], ['user_id', $user->id]])->first();
        $mailSubject = $mailTemplate->email_subject;
        $mailBody = $mailTemplate->email_body;

        // second, send a password reset link to user via email
        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $website_title = $user->shop_name;
        $link = '<a href=' . route('customer.orders-details', ['id' => $order->id, $user->username]) . '>Order Details</a>';
        $mailBody = str_replace('{customer_name}', $order->billing_fname . ' ' . $order->billing_lname, $mailBody);
        $mailBody = str_replace('{order_number}', $order->order_number, $mailBody);

        $mailBody = str_replace('{shipping_fname}', $order->shipping_fname, $mailBody);
        $mailBody = str_replace('{shipping_lname}', $order->shipping_lname, $mailBody);
        // Monta endereço de envio conforme colunas reais
        $shipping_address = trim(
            ($order->shipping_street ?? '') . ', ' .
            ($order->shipping_number_address ?? '') . ', ' .
            ($order->shipping_neighborhood ?? '') . ', ' .
            ($order->shipping_zip ?? '') .
            (!empty($order->shipping_reference) ? ' - ' . $order->shipping_reference : '')
        );
        $mailBody = str_replace('{shipping_address}', $shipping_address, $mailBody);
        $mailBody = str_replace('{shipping_city}', $order->shipping_city, $mailBody);
        $mailBody = str_replace('{shipping_country}', $order->shipping_country, $mailBody);
        $mailBody = str_replace('{shipping_number}', $order->shipping_number, $mailBody);

        $mailBody = str_replace('{billing_fname}', $order->billing_fname, $mailBody);
        $mailBody = str_replace('{billing_lname}', $order->billing_lname, $mailBody);
        $mailBody = str_replace('{billing_city}', $order->billing_city, $mailBody);
        $mailBody = str_replace('{billing_number}', $order->billing_number, $mailBody);
        $mailBody = str_replace('{order_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $website_title, $mailBody);

        $data = [];
        $data['smtp_status'] = $info->is_smtp;
        $data['smtp_host'] = $info->smtp_host;
        $data['smtp_port'] = $info->smtp_port;
        $data['encryption'] = $info->encryption;
        $data['smtp_username'] = $info->smtp_username;
        $data['smtp_password'] = $info->smtp_password;

        //mail info in array
        $data['from_mail'] = $info->from_mail;
        $data['recipient'] = $order->billing_email;
        $data['subject'] = $mailSubject;
        $data['body'] = $mailBody;
        $data['invoice'] = public_path('assets/front/invoices/' . $order->invoice_number);
        BasicMailer::sendMail($data);
        return;
    }

    public static function getUserCurrentLanguage($userId)
    {
        if (session()->has('user_lang')) {
            $code = str_replace('user_', '', session()->get('user_lang'));
            $userCurrentLang = UserLanguage::where('code', $code)->where('user_id', $userId)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $userId)->first();
                if ($userCurrentLang) {
                    session()->put('user_lang', $userCurrentLang->code);
                }
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $userId)->first();
        }
        
        // Se ainda não encontrou idioma, criar um idioma padrão para o usuário
        if (!$userCurrentLang) {
            $userCurrentLang = UserLanguage::create([
                'name' => 'English',
                'code' => 'en',
                'is_default' => 1,
                'rtl' => 0,
                'type' => 'admin',
                'user_id' => $userId,
                'keywords' => json_encode([])
            ]);
        }
        
        return $userCurrentLang;
    }

    public static function getUserCurrentCurrency($userId)
    {
        if (session()->has('user_curr')) {
            $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->where('user_id', $userId)->first();
            if (empty($userCurrentCurr)) {
                $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $userId)->first();
                session()->put('user_curr', $userCurrentCurr->id);
            }
        } else {
            $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $userId)->firstOrFail();
        }
        return $userCurrentCurr;
    }

    public static function get_keywords($userId)
    {
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $userId)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $userId)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $userId)->first();
        }
        return json_decode($userCurrentLang->keywords, true);
    }
}
