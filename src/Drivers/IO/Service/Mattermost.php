<?php


    setFn('Open', function ($Call)
    {
        return $Call;
    });

    setFn('Write', function ($Call)
    {
        $Data =
            [
                'channel'  => $Call['Mattermost']['Channel'],
                'username' => $Call['Mattermost']['Username'],
                'text'     => $Call['Data']
            ];

        $Data = ['payload' => j($Data)];

        return $Result = F::Run('IO', 'Write',
            [
                'Storage' => 'Web',
                'Where'   => $Call['Mattermost']['Webhook URL'].'/'.$Call['Mattermost']['Key'],
                'Data'    => $Data
            ]);

    });

    setFn('Read', function ($Call)
    {
        return $Call;
    });