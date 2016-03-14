<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Prepare', function ($Call)
    {
        $Call['Output']['Form'][] = '<js>https://www.google.com/recaptcha/api.js</js>'.
        '<div class="g-recaptcha" data-sitekey="'.$Call['ReCAPTCHA']['Public'].'"></div>';

        return $Call;
    });

    setFn('Check', function ($Call)
    {
        if (isset($Call['Request']['g-recaptcha-response']))
        {
            $Result = F::Run('IO', 'Write',
            [
                'Storage'       => 'Web',
                'Where'         => $Call['ReCAPTCHA']['Endpoint'],
                'Output Format' => 'Formats.JSON',
                'Data'          =>
                [
                    'secret'    => $Call['ReCAPTCHA']['Private'],
                    'response'  => $Call['Request']['g-recaptcha-response'],
                    'remoteip'  => $Call['HTTP']['IP']
                ]
            ]);

            $Result = array_pop($Result);
        }
        else
            $Result = ['success' => false];

        if (isset($Result['success']))
        {
            if ($Result['success'])
                ;
            else
            {
                $Call = F::Hook('CAPTCHA.Failed', $Call);
                $Call['Errors'][] = ['CAPTCHA' => 'Failed'];
            }
        }

        return $Call;
    });