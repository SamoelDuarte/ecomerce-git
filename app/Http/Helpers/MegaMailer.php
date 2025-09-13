<?php

namespace App\Http\Helpers;

use App\Models\BasicExtended;
use App\Models\EmailTemplate;
use App\Models\Language;
use App\Models\User\UserEmailTemplate;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class MegaMailer
{

    public function mailFromAdmin($data)
    {
        $temp = EmailTemplate::where('email_type', '=', $data['templateType'])->first();
        $body = $temp->email_body;
        if (array_key_exists('username', $data)) {
            $body = preg_replace("/{username}/", $data['username'], $body);
        }
        if (array_key_exists('replaced_package', $data)) {
            $body = preg_replace("/{replaced_package}/", $data['replaced_package'], $body);
        }
        if (array_key_exists('removed_package_title', $data)) {
            $body = preg_replace("/{removed_package_title}/", $data['removed_package_title'], $body);
        }
        if (array_key_exists('package_title', $data)) {
            $body = preg_replace("/{package_title}/", $data['package_title'], $body);
        }
        if (array_key_exists('package_price', $data)) {
            $body = preg_replace("/{package_price}/", $data['package_price'], $body);
        }
        if (array_key_exists('activation_date', $data)) {
            $body = preg_replace("/{activation_date}/", $data['activation_date'], $body);
        }
        if (array_key_exists('expire_date', $data)) {
            $body = preg_replace("/{expire_date}/", $data['expire_date'], $body);
        }
        if (array_key_exists('requested_domain', $data)) {
            $body = preg_replace("/{requested_domain}/", "<a href='http://" . $data['requested_domain'] . "'>" . $data['requested_domain'] . "</a>", $body);
        }
        if (array_key_exists('previous_domain', $data)) {
            $body = preg_replace("/{previous_domain}/", "<a href='http://" . $data['previous_domain'] . "'>" . $data['previous_domain'] . "</a>", $body);
        }
        if (array_key_exists('current_domain', $data)) {
            $body = preg_replace("/{current_domain}/", "<a href='http://" . $data['current_domain'] . "'>" . $data['current_domain'] . "</a>", $body);
        }
        if (array_key_exists('subdomain', $data)) {
            $body = preg_replace("/{subdomain}/", "<a href='http://" . $data['subdomain'] . "'>" . $data['subdomain'] . "</a>", $body);
        }
        if (array_key_exists('last_day_of_membership', $data)) {
            $body = preg_replace("/{last_day_of_membership}/", $data['last_day_of_membership'], $body);
        }
        if (array_key_exists('login_link', $data)) {
            $body = preg_replace("/{login_link}/", $data['login_link'], $body);
        }
        if (array_key_exists('customer_name', $data)) {
            $body = preg_replace("/{customer_name}/", $data['customer_name'], $body);
        }
        if (array_key_exists('verification_link', $data)) {
            $body = preg_replace("/{verification_link}/", $data['verification_link'], $body);
        }
        if (array_key_exists('website_title', $data)) {
            $body = preg_replace("/{website_title}/", $data['website_title'], $body);
        }



        // Fallback to system SMTP
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $be = $currentLang->basic_extended;

        if ($be->is_smtp == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $be->smtp_host,
                    'port' => $be->smtp_port,
                    'encryption' => $be->encryption,
                    'username' => $be->smtp_username,
                    'password' => $be->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);

                Mail::send([], [], function (Message $message) use ($data, $be, $body, $temp) {
                    $message->to($data['toMail'])
                        ->from($be->from_mail, $be->from_name)
                        ->subject($temp->email_subject)
                        ->html($body, 'text/html');

                    if (array_key_exists('membership_invoice', $data)) {
                        $filePath = public_path('assets/front/invoices/') . $data['membership_invoice'];
                        if (file_exists($filePath)) {
                            $message->attach($filePath);
                        }
                    }
                });

                // Cleanup invoice if needed
                if (array_key_exists('membership_invoice', $data)) {
                    @unlink(public_path('assets/front/invoices/') . $data['membership_invoice']);
                }
            } catch (\Exception $e) {
                if (array_key_exists('membership_invoice', $data)) {
                    @unlink(public_path('assets/front/invoices/') . $data['membership_invoice']);
                }
                Session::flash('error', 'Mail could not be sent: ' . $e->getMessage());
            }
        }
    }

    public function mailFromUser($data)
    {
        $user = getUser();
        $temp = UserEmailTemplate::where('email_type', '=', $data['templateType'])->where('user_id', $user->id)->first();
        if ($temp) {
            $body = $temp->email_body;
            if (array_key_exists('username', $data)) {
                $body = preg_replace("/{username}/", $data['username'], $body);
            }
            if (array_key_exists('customer_name', $data)) {
                $body = preg_replace("/{customer_name}/", $data['customer_name'], $body);
            }
            if (array_key_exists('order_number', $data)) {
                $body = preg_replace("/{order_number}/", $data['order_number'], $body);
            }
            if (array_key_exists('order_link', $data)) {
                $body = preg_replace("/{order_link}/", $data['order_link'], $body);
            }
            if (array_key_exists('website_title', $data)) {
                $body = preg_replace("/{website_title}/", $data['website_title'], $body);
            }

            if ($user->smtp_status == 1) {
                try {
                    // Configure SMTP with user settings
                    $smtp = [
                        'transport' => 'smtp',
                        'host' => $user->smtp_host,
                        'port' => $user->smtp_port,
                        'encryption' => $user->encryption,
                        'username' => $user->smtp_username,
                        'password' => $user->smtp_password,
                        'timeout' => null,
                        'auth_mode' => null,
                    ];
                    Config::set('mail.mailers.smtp', $smtp);

                    // Send mail using Laravel's Mail facade
                    Mail::send([], [], function (Message $message) use ($data, $user, $body, $temp) {
                        $message->to($data['toMail'], $data['toName'])
                            ->from($user->email, $user->from_name)
                            ->subject($temp->email_subject)
                            ->html($body, 'text/html');

                        // Add attachments if any
                        if (array_key_exists('order_number', $data)) {
                            $message->attach(public_path('assets/front/invoices/' . $data['attachment']));
                        }
                    });

                } catch (\Exception $e) {
                    Session::flash('error', 'Mail could not be sent: ' . $e->getMessage());
                }
            } else {
                Session::flash('error', 'SMTP is not configured for this user.');
            }
        }
    }

    public function mailToAdmin($data)
    {
        $be = BasicExtended::first();
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {

                $mail->isSMTP();
                $mail->Host = $be->smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $be->smtp_username;
                $mail->Password = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port = $be->smtp_port;
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
            }
        }
        try {
            $mail->setFrom($data['fromMail'], $data['fromName']);
            $mail->addAddress($be->from_mail);     // Add a recipient

            // Attachments
            if (array_key_exists('attachments', $data)) {
                $mail->addAttachment('front/invoices/' . $data['attachments']); // Add attachments
            }

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = $data['subject'];
            $mail->Body = $data['body'];

            $mail->send();
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }
    }
    public function mailContactMessage($data)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $be = $currentLang->basic_extended;
        $mail = new PHPMailer(true);
        if ($be->is_smtp == 1) {
            try {
                $mail->isSMTP();
                $mail->Host       = $be->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $be->smtp_username;
                $mail->Password   = $be->smtp_password;
                $mail->SMTPSecure = $be->encryption;
                $mail->Port       = $be->smtp_port;
            } catch (Exception $e) {
                Session::flash('error', $e);
                return back();
            }
        }

        try {
            //Recipients
            $mail->setFrom($be->from_mail, $be->from_name);
            $mail->addAddress($data['toMail'], $data['toName']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body    = $data['body'];
            $mail->send();
        } catch (Exception $e) {
            Session::flash('error', $e);
            return back();
        }
    }
}
