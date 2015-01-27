<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Recognize', function ($Call)
    {
        $Call['Captcha ID'] = F::Run(null, 'Send', $Call);
        return $Call;
    });

    setFn('Send', function ($Call)
    {
        F::Run('IO', 'Write',
            [
                'Storage'   => 'Web',
                'Where'     =>
                [
                    'ID'    => $Call['Anti-Captcha']['In URL']
                ],
                'Data'      =>
                [
                    'method'    => 'base64',
                    'key'       => $Call['Anti-Captcha']['User Key'],
                    'body'      => urlencode($Call['CAPTCHA'])
                ]
            ]);

        return $Call;
    });

    setFn('Receive', function ($Call)
    {

        return $Call;
    });