<?php

namespace App\Http\Controllers\User;

use Session;
use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Models\BasicExtended as AdminBasicExtended;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\User\UserNewsletterSubscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;
        $data['subscs'] = UserNewsletterSubscriber::where('user_id', Auth::guard('web')->user()->id)
            ->when($term, function ($query, $term) {
                return $query->where('email', 'LIKE', '%' . $term . '%');
            })->orderBy('id', 'DESC')->paginate(10);
        return view('user.subscribers.index', $data);
    }

    //Usersubscribe

    public function Usersubscribe($domain, Request $request)
    {
        $user = getUser();
        if ($user) {
            $user_id = $user->id;
        } else {
            return response()->json([
                'success' => __('Something went wrong')
            ], 200);
        }

        $rules = [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($user_id) {
                    $subcriber = UserNewsletterSubscriber::where([['user_id', $user_id], ['email', $value]])->count();
                    if ($subcriber > 0) {
                        return $fail(__('The email address has already been taken'));
                    }
                }
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->getMessageBag()], 400);
        }
        $user = getUser();
        $id = $user->id;
        $subsc = new UserNewsletterSubscriber();
        $subsc->email = $request->email;
        $subsc->user_id = $id;
        $subsc->save();
        return response()->json([
            'success' => __('You have successfully subscribed to our newsletter')
        ], 200);
    }

    public function mailsubscriber()
    {
        return view('user.subscribers.mail');
    }

    public function getMailInformation()
    {
        $data['user'] = Auth::guard('web')->user();
        return view('user.subscribers.mail-information', $data);
    }

    public function storeMailInformation(Request $request)
    {
        $rules = [
            'email' => 'required',
            'from_name' => 'required',
            'smtp_host' => 'required_if:smtp_status,1',
            'smtp_port' => 'required_if:smtp_status,1',
            'smtp_username' => 'required_if:smtp_status,1',
            'smtp_password' => 'required_if:smtp_status,1'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::guard('web')->user();

        $user->update([
            'smtp_status' => $request->filled('smtp_status') ? 1 : 0,
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'encryption' => $request->encryption ?? 'tls',
            'smtp_username' => $request->smtp_username,
            'smtp_password' => $request->smtp_password,
            'email' => $request->email,
            'from_name' => $request->from_name,
            'from_mail' => $request->email
        ]);
          

        Session::flash('success', $keywords['Successfully updated email information!'] ?? __('Successfully updated email information!'));
        return back();
    }
    public function subscsendmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $sub = $request->subject;
        $msg = $request->message;
        $user = Auth::guard('web')->user();

        $subscs = UserNewsletterSubscriber::where('user_id', $user->id)->get();

        /******** Send mail to user using lojista's SMTP ********/
        $data = [];
        $data['subject'] = $sub;
        $data['body'] = $msg;

        foreach ($subscs as $key => $subsc) {
            $data['recipient'] = $subsc->email;
            BasicMailer::sendMailFromUser($user, $data);
        }
        Session::flash('success', __('The mail has been sent successfully'));
        return back();
    }


    public function delete(Request $request)
    {
        UserNewsletterSubscriber::findOrFail($request->subscriber_id)->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            UserNewsletterSubscriber::findOrFail($id)->delete();
        }
        Session::flash('success', __('Deleted successfully'));
        return "success";
    }

    public function testMailConfiguration(Request $request)
    {
        $rules = [
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_username' => 'required',
            'smtp_password' => 'required',
            'test_email' => 'required|email',
            'from_email' => 'required|email'
        ];

        $messages = [
            'test_email.required' => __('O email de destino é obrigatório'),
            'test_email.email' => __('Informe um email válido para o teste'),
            'from_email.required' => __('O email de remetente é obrigatório'),
            'from_email.email' => __('Informe um email de remetente válido'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Log detalhado ANTES de enviar
            \Log::info('========================================');
            \Log::info('🔍 TESTE DE SMTP INICIADO');
            \Log::info('========================================');
            \Log::info('📧 Host: ' . $request->smtp_host);
            \Log::info('🔌 Port: ' . $request->smtp_port);
            \Log::info('🔐 Encryption: ' . ($request->encryption ?? 'tls'));
            \Log::info('👤 Username: ' . $request->smtp_username);
            \Log::info('🔑 Password Length: ' . strlen($request->smtp_password) . ' caracteres');
            \Log::info('📨 From Email: ' . $request->from_email);
            \Log::info('📮 Test Email: ' . $request->test_email);
            \Log::info('👨‍💼 From Name: ' . ($request->from_name ?? 'Teste SMTP'));
            \Log::info('----------------------------------------');

            $smtp = [
                'transport' => 'smtp',
                'host' => $request->smtp_host,
                'port' => (int) $request->smtp_port,
                'encryption' => $request->encryption ?? 'tls',
                'username' => $request->smtp_username,
                'password' => $request->smtp_password,
                'timeout' => null,
                'auth_mode' => null,
            ];
            
            \Config::set('mail.mailers.smtp', $smtp);
            \Log::info('✅ Configuração SMTP aplicada');

            $testEmail = $request->test_email;
            $fromEmail = $request->from_email;
            $fromName = $request->from_name ?? 'Teste SMTP';
            $shopName = Auth::guard('web')->user()->company_name ?? Auth::guard('web')->user()->username;

            \Log::info('📤 Tentando enviar email...');

            \Mail::send([], [], function ($message) use ($testEmail, $fromEmail, $fromName, $shopName, $request) {
                $message->to($testEmail)
                    ->from($fromEmail, $fromName)
                    ->cc($fromEmail, $fromName) // Cópia para o remetente
                    ->subject('✅ Teste de Configuração SMTP - ' . $shopName)
                    ->html('
                        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                            <h2 style="color: #28a745; text-align: center;">✅ Teste de SMTP Bem-Sucedido!</h2>
                            <hr style="border: 1px solid #eee;">
                            <p>Parabéns! Se você está lendo este email, significa que suas configurações SMTP estão <strong>funcionando corretamente</strong>.</p>
                            
                            <h3 style="color: #333;">📋 Informações da Configuração:</h3>
                            <ul style="line-height: 1.8;">
                                <li><strong>Loja:</strong> ' . $shopName . '</li>
                                <li><strong>Servidor SMTP:</strong> ' . $request->smtp_host . '</li>
                                <li><strong>Porta:</strong> ' . $request->smtp_port . '</li>
                                <li><strong>Criptografia:</strong> ' . strtoupper($request->encryption ?? 'TLS') . '</li>
                                <li><strong>Usuário:</strong> ' . $request->smtp_username . '</li>
                                <li><strong>Email de Remetente:</strong> ' . $fromEmail . '</li>
                                <li><strong>Nome do Remetente:</strong> ' . $fromName . '</li>
                            </ul>
                            
                            <h3 style="color: #333;">⏰ Data/Hora do Teste:</h3>
                            <p>' . date('d/m/Y H:i:s') . '</p>
                            
                            <hr style="border: 1px solid #eee; margin-top: 20px;">
                            <p style="color: #666; font-size: 12px; text-align: center;">
                                Este é um email de teste automático. Você pode ignorá-lo com segurança.
                            </p>
                        </div>
                    ', 'text/html');
            });

            \Log::info('✅ Email ENVIADO com sucesso!');
            \Log::info('📬 Destinatário: ' . $testEmail);
            \Log::info('========================================');

            return response()->json([
                'success' => true,
                'message' => '✅ Email de teste enviado com sucesso para ' . $testEmail . '. Verifique sua caixa de entrada e também a pasta de SPAM. Pode demorar alguns minutos.'
            ]);
        } catch (\Exception $e) {
            \Log::error('========================================');
            \Log::error('❌ ERRO AO ENVIAR EMAIL DE TESTE');
            \Log::error('========================================');
            \Log::error('Mensagem: ' . $e->getMessage());
            \Log::error('Arquivo: ' . $e->getFile());
            \Log::error('Linha: ' . $e->getLine());
            \Log::error('Stack Trace:');
            \Log::error($e->getTraceAsString());
            \Log::error('========================================');
            
            return response()->json([
                'success' => false,
                'message' => '❌ Erro ao enviar: ' . $e->getMessage()
            ], 500);
        }
    }
}
