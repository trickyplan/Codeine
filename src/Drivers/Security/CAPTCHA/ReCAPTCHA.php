<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Prepare', function ($Call)
    {
        $Call['Output']['ReCAPTCHA'][] =
            '<script src="https://www.google.com/recaptcha/api.js?render='.$Call['ReCAPTCHA']['Public'].'&hl='.$Call['Locale'].'" ></script>'.
            '<div class="g-recaptcha" data-sitekey="'.$Call['ReCAPTCHA']['Public'].'"'.
            ' data-action='.$Call['ReCAPTCHA']['Action'].'></div>'.
            '<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" />'.
            '<js>Security/CAPTCHA:ReCAPTCHA</js>';

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
            $Result = ['success' => false, 'score' => 0];

        if (isset($Result['success']) && isset($Result['score']))
        {
            if ($Result['success'] && floatval($Result['score']) > 0.5)
                ;
            else
            {
                $Call = F::Hook('CAPTCHA.Failed', $Call);
                $Call['Errors']['CAPTCHA'][] = 'Failed';
            }
        }

        return $Call;
    });