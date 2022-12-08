<?php

namespace App\Library;

use Illuminate\Support\Facades\Mail;

class EmailSender
{
  public static function sendEmail($recipient, $data = [])
  {
    Mail::send('mail', $data, function ($message) use ($recipient) {
      $message->to($recipient, '')->subject('MoA Password Reset Code');
      $message->from('test@softdemo.net', "MoA Mail service");
    });
  }
}