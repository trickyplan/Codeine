<?php

    include 'Codeine/Core.php';

    try
    {
        if (file_exists(Root.'/locks/down') && !isset($_COOKIE['Magic']))
            throw new Exception('down');

        $Call = F::Bootstrap
        ([
            'Path' => [Root],
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

            default:
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Status: 503 Service Temporarily Unavailable');

                if (file_exists(Root.'/down.html'))
                    readfile(Root.'/down.html');
                else
                    readfile(Codeine.'/down.html');
            break;
        }
    }

    F::Shutdown($Call);

    fastcgi_finish_request();