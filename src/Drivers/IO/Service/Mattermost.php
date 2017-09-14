<?php


    setFn('Open', function ($Call)
    {
        return $Call;
    });

    setFn('Write', function ($Call)
    {
        $Data =
            [
                'channel'  => empty($Call['MattermostChannel'])?$Call['Default']['MattermostChannel']:$Call['MattermostChannel'],
                'username' => empty($Call['Username'])?$Call['Default']['Username']:$Call['Username'],
                'text'     => $Call['Data']
            ];
        $Data = ['payload' => j($Data)];

        return $Result = F::Run('IO', 'Write',
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