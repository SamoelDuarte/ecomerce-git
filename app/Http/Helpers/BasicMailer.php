<?php

namespace App\Http\Helpers;

use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Str;

class BasicMailer
{
  public static function sendMailFromUser($user, $data)
  {
    // Check if user has SMTP configured
    if ($user->smtp_status == 1 && !empty($user->smtp_host)) {
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

      try {
        Mail::send([], [], function (Message $message) use ($data, $user) {
          $fromEmail = $user->email ?? $user->from_mail;
          $fromName = $user->from_name ?? $user->username;
          
          $message->to($data['recipient'])
            ->from($fromEmail, $fromName)
            ->cc($fromEmail, $fromName) // Envia cópia para o remetente
            ->subject($data['subject'])
            ->html($data['body'], 'text/html');

          if (array_key_exists('invoice', $data)) {
            $message->attach($data['invoice']);
          }
        });
        return true;
      } catch (\Exception $e) {
        Log::info('Mail sending error: ' . $e->getMessage());
        Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . Str::limit($e->getMessage(), 120));
        return false;
      }
    } else {
      // Fallback to admin SMTP if user doesn't have SMTP configured
      $be = \App\Models\BasicExtended::first();
      if ($be && $be->is_smtp == 1) {
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;
        $data['from_mail'] = $user->email ?? $be->from_mail;
        return self::sendMail($data);
      }
      return false;
    }
  }

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
          $fromName = $data['from_name'] ?? $fromMail;
          $subject = $data['subject'];
          
          $message->to($data['recipient'])
            ->from($fromMail, $fromName)
            ->cc($fromMail, $fromName) // Envia cópia para o remetente
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
