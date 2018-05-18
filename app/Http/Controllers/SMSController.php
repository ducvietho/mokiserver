<?php

namespace App\Http\Controllers;



use Softon\Sms\Sms;

class SMSController extends Controller
{
    public static function otpVerify()
    {

        $sms = Sms::send_raw('0988973418','otpSMS');
    }
}
