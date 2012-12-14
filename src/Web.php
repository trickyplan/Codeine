<?php

    /**
     * @author BreathLess
     * @date 27.28.11
     * @time 5:17
     */


    if (file_exists(Root.'/locks/down') && !isset($_COOKIE['Magic']))
    {
        readfile(__DIR__.'/down.html');
        die();
    }

    include 'Codeine/Core.php';

    F::Bootstrap ([
                    'Path' => [Root]
                  ]);

    $Call = F::Run(
        'System.Interface.Web',
        'Run',
        [
            'Service' => 'Code.Flow.Front',
            'Method'  => 'Run'
        ]
    );

    F::Shutdown($Call);

    fastcgi_finish_request();