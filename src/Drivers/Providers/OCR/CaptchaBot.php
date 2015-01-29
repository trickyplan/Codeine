<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Recognize', function ($Call)
    {
        $Result = F::Run('Code.Run.XML-RPC', 'Run',
        [
            'Service' => $Call['CaptchaBot']['Endpoint'],
            'Method'  => 'ocr_server::analyze',
            'Call'    =>
            [
                ['param' => ['base64' => base64_encode($Call['Image'])]],
                ['param' =>  ['string' => $Call['CaptchaBot']['User']]],
                ['param' =>  ['string' => $Call['CaptchaBot']['Password']]],
                ['param' => ['integer' => 0]],
            ]
        ]);

        return $Result;
    });