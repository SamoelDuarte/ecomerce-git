<?php

namespace App\Http\Helpers;

use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Str;

class BasicMailer
{
  public static function sendMail($data)
  {
    if ($data['smtp_status'] == 1) {
      $smtp = [
        'transport' => 'smtp',
        'host' => $data['smtp_host'],
        'port' => $data['smtp_port'],
        'encryption' => $data['encryption'],
        'username' => $data['smtp_username'],
        'password' => $data['smtp_password'],
        'timeout' => null,
        'auth_mode' => null,
      ];
      Config::set('mail.mailers.smtp', $smtp);

      // add other informations and send the mail
      try {
        Mail::send([], [], function (Message $message) use ($data) {
          $fromMail = $data['from_mail'];
          $subject = $data['subject'];
          $message->to($data['recipient'])
            ->from($fromMail)
            ->subject($subject)
            ->html($data['body'], 'text/html');

          if (array_key_exists('invoice', $data)) {
            $message->attach($data['invoice']);
          }
        });
      } catch (\Exception $e) {
        Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . Str::limit($e->getMessage(), 120));
      }
    }
  }
}
