<?php


    setFn('Open', function ($Call)
    {
        return $Call;
    });

    setFn('Write', function ($Call)
    {
        file_put_contents("/home/user/work/test.log", 'Channel'.PHP_EOL.print_r($Call['Channel'], true) . PHP_EOL, FILE_APPEND);
        file_put_contents("/home/user/work/test.log", 'Data'.PHP_EOL.print_r($Call['Data'], true) . PHP_EOL, FILE_APPEND);
        $Data =
            [
                'channel'  => empty($Call['MattermostChannel'])?$Call['Default']['MattermostChannel']:$Call['MattermostChannel'],
                'username' => empty($Call['Username'])?$Call['Default']['Username']:$Call['Username'],
                'text'     => $Call['Data']
            ];
        $Data = ['payload' => j($Data)];

//        return
            $Result = F::Run('IO', 'Write',
            [
                'Storage' => 'Web',
                'Where'   => $Call['Webhook URL'],
                'Data'    => $Data
            ]);
        file_put_contents("/home/user/work/test.log", 'Result'.PHP_EOL.print_r($Result, true) . PHP_EOL, FILE_APPEND);
            return $Result;
    });

    setFn('Read', function ($Call)
    {
        return $Call;
    });