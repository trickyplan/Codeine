<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Open', function ($Call)
    {
        return true;
    });

    setFn('Write', function ($Call)
    {
        $Data =
            [
                'channel'  => $Call['Channel'],
                'username' => $Call['Username'],
                'text'     => $Call['Data']
            ];

        return F::Run('IO', 'Write',
            [
                'Storage' => 'Web',
                'Where'   => $Call['Webhook URL'],
                'Data'    => j($Data)
            ]);
    });

    setFn('Read', function ($Call)
    {

        return $Call;
    });