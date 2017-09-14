<?php


    setFn('Open', function ($Call)
    {
        return true;
    });

    setFn('Write', function ($Call)
    {
        $Data =
            [
                'channel'  => empty($Call['Channel'])?$Call['Default']['Channel']:$Call['Channel'],
                'username' => empty($Call['Username'])?$Call['Default']['Username']:$Call['Username'],
                'text'     => $Call['Data']
            ];
        $Data = ['payload' => j($Data)];

        return F::Run('IO', 'Write',
            [
                'Storage' => 'Web',
                'Where'   => $Call['Webhook URL'],
                'Data'    => $Data
            ]);
    });

    setFn('Read', function ($Call)
    {
        return $Call;
    });