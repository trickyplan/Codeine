<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y


    include 'Codeine/Core.php';

    $Opts = [];

    foreach ($argv as $arg)
        if (preg_match('/--(\S+)\=(\S+)/', $arg, $Pockets))
            $Opts = F::Dot($Opts, $Pockets[1], $Pockets[2]);

    !defined('Root')? define('Root', Codeine): false;

    $Call = F::Bootstrap
    ([
        'Path' => [Root],
        'Environment' => isset($Opts['Environment'])? $Opts['Environment']: 'Production',
        'Service' => 'System.Interface.CLI',
        'Method' => 'Run',
        'Call' =>
        [
            'Service' => 'Code.Flow.Daemon',
            'Method'  => 'Run',
            'Execute' => $Opts
        ]
    ]);

    F::Shutdown($Call);

    exit(0);