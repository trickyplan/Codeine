<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y


    include 'Codeine/Core.php';

    $Call = F::Bootstrap
        ([
            'Path' => [Root],
            'Service' => 'System.Interface.Web',
            'Method' => 'Do',
            'Call' =>
            [
                'Service' => 'Code.Flow.Daemon',
                'Method'  => 'Run',
                'Call'    =>
                [
                    'DaemonID' => 'codeine-delayed',
                    'Execute' =>
                    [
                        'Service' => 'Code.Run.Delayed',
                        'Method'  => 'Execute'
                    ],
                    'Trigger' =>
                    [
                        'Service' => 'Code.Run.Delayed',
                        'Method'  => 'Queue'
                    ]
                ]
            ]
        ]);

    exit(0);