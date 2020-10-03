<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;

class MailController extends Controller
{
    public static function sendSignupEmail($name, $email, $verificationCode)
    {
        $data = [
            'name' => $name,
            'verification_code' => $verificationCode
        ];
        Mail::to($email)->send(new SignupEmail($data));
    }
}
