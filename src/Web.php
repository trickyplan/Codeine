<?php

    include 'Codeine/Core.php';

    $Call = [];

    try
    {
        if (file_exists(Root.'/locks/down') && !isset($_COOKIE['Magic']))
            throw new Exception('down');

        $Call = F::Bootstrap
        ([
            'Paths' => [Root],
            'Service' => 'System.Interface.Web',
            'Method' => 'Do',
            'Call' =>
            [
                'Service' => 'Code.Flow.Front',
                'Method'  => 'Run'
            ]
        ]);
    }
    catch (Exception $e)
    {
        switch ($_SERVER['Environment'])
        {
            case 'Development':
                d(__FILE__, __LINE__, $e);
            break;
        }

        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');

        if (file_exists(Root.'/Public/down.html'))
            readfile(Root.'/Public/down.html');
        else
            readfile(Codeine.'/down.html');
    }

    F::Shutdown($Call);