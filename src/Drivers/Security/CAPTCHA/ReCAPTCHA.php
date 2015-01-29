<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Widget', function ($Call)
    {


        return $Call;
    });

    setFn('Check', function ($Call)
    {
        $CAPTCHA = curl_init();
        $Data =
            [
                'privatekey' => $Call['ReCAPTCHA']['Private'],
                'remoteip' => $_SERVER['REMOTE_ADDR'],
                'challenge' => $Call['Request']['recaptcha.challenge.field'],
                'response' => $Call['Request']['recaptcha.response.field']
            ];

        curl_setopt_array($CAPTCHA,
            [
                CURLOPT_URL => 'http://www.google.com/recaptcha/api/verify',
                CURLOPT_POST => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $Data // FIXME IO
            ]);

        list($Result) = explode("\n", curl_exec($CAPTCHA));

        if ($Result == 'false')
        {
            $Call['Failure'] = true;
            $Call = F::Hook('CAPTCHA.Failed', $Call);
        }

        return $Call;
    });