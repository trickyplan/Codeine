<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y


    include 'Codeine/Core.php';

    F::Bootstrap ([
                       'Path' => array(Root)
                       //,'Trace' => true
                  ]);

    F::Run('Code.Flow.Daemon', 'Run', [
        'DaemonID' => 'codeine-monitor',
        'Execute' =>
            [
                'Service' => 'Monitor.Collect',
                'Method'  => 'Do'
            ],
        'Trigger' =>
            [
                'Service' => 'Code.Triggers.Period',
                'Method'  => 'Check',
                'Call' =>
                    [
                        'Period' => 1
                    ]
            ]
    ]);


    exit(0);