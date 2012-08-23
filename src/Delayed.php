<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y


    include 'Codeine/Core.php';

    F::Bootstrap (array(
                       'Path' => array(Root)
                       //,'Trace' => true
                  ));

    F::Run('Code.Flow.Daemon', 'Run', [
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
    ]);


    exit(0);