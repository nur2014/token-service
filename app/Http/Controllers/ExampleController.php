<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function emailSend()
    {
        $data = [
            'name' => 'Moktar'
        ];

        Mail::send('mail', $data, function ($message) {
            $message->to('im.moktar@gmail.com', 'Moktar Ali')->subject('Test email from MoA.');
            $message->from('test@softdemo.net', "MoA Mail service");
        });

        echo "check your inbox";
    }
}
