<?php
    
    if (file_exists(Root.'/vendor/autoload.php'))
        include Root.'/vendor/autoload.php';
    
    require 'Codeine/Core.php';

    $Call = [];

        if (file_exists(Root.'/locks/down') && !isset($_COOKIE['Magic']))
        {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 3600');

            header('X-Reason: Locked');
            if (file_exists(Root.'/Public/down.html'))
                readfile(Root.'/Public/down.html');
            else
                readfile(Codeine.'/down.html');

            die();
        }
    
        $Call = F::Bootstrap
        ([
            'Paths' => [Root],
            'Service' => 'System.Interface.HTTP',
            'Method' => 'Do',
            'Call' =>
            [
                'Service' => 'Code.Flow.API',
                'Method'  => 'Run'
            ],
            'Watch' => isset($_REQUEST['WATCH'])? $_REQUEST['WATCH']: null
        ]);


    F::Shutdown($Call);