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

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $be = $currentLang->basic_extended;

        if ($be->is_smtp == 1) {
            try {
                //config smtp
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
                //set data to for pass in te mail array
                $mailData = [];
                $mailData['from_mail'] = $be->from_mail;
                $mailData['toMail'] = $data['toMail'];
                $mailData['subject'] = $temp->email_subject;
                $mailData['body'] = $body;
                if (array_key_exists('membership_invoice', $data)) {
                    $mailData['membership_invoice'] = $data['membership_invoice'];
                }
                //send mail
                Mail::send([], [], function (Message $message) use ($mailData) {
                    $message->to($mailData['toMail'])
                        ->from($mailData['from_mail'])
                        ->subject($mailData['subject'])
                        ->html($mailData['body'], 'text/html');

                    if (array_key_exists('membership_invoice', $mailData)) {
                        $filePath = public_path('assets/front/invoices/') . $mailData['membership_invoice'];

                        if (file_exists($filePath)) {
                            $message->attach($filePath);
                        }
                    }
                });
                // Attachments
                if (array_key_exists('membership_invoice', $mailData)) {
                    @unlink(public_path('assets/front/invoices/') . $mailData['membership_invoice']);
                }
            } catch (TransportException $e) {
                // Attachments
                if (array_key_exists('membership_invoice', $mailData)) {
                    @unlink(public_path('assets/front/invoices/') . $mailData['membership_invoice']);
                }
                Session::flash('error', 'Mail could not be sent.');
                return;
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

            if (session()->has('lang')) {
                $currentLang = Language::where('code', session()->get('lang'))->first();
            } else {
                $currentLang = Language::where('is_default', 1)->first();
            }

            $be = $currentLang->basic_extended;

            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';


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
                }
            }

            try {

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($data['toMail'], $data['toName']);

                // Attachments
                if (array_key_exists('order_number', $data)) {
                    $mail->addAttachment('assets/front/invoices/' . $data['attachment']);
                }

                // Content
                $mail->isHTML(true);
                $mail->Subject = $temp->email_subject;
                $mail->Body    = $body;

                $mail->send();
            } catch (Exception $e) {
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
