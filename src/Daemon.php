<?php

    /**
     * @author bergstein@trickyplan.com
     * @time 5:17
     */

    // 10xs for http://leonid.shevtsov.me/ru/mnogoprocessovye-demony-na-php#ixzz23J4hMu6y

    include 'Codeine/Core.php';

    $Opts = [];

    foreach ($argv as $arg)
        if (preg_match('/--(\S+)\=(\S+)/', $arg, $Pockets))
            $Opts = F::Dot($Opts, $Pockets[1], $Pockets[2]);

    if (file_exists('/etc/default/codeine'))
        $Opts['Path'] = file_get_contents('/etc/default/codeine');

    if (isset($Opts['Path']))
        define('Root', $Opts['Path']);
    else
        !defined('Root')? define('Root', getcwd()): false;

    $Call = F::Bootstrap
    ([
        'Paths' => [Root],
        'Service' => 'System.Interface.CLI',
        'Method' => 'Do',
        'Call' =>
        [
            'Service' => 'Code.Flow.Daemon',
            'Method'  => 'Run',
            'Execute' => $Opts
        ]
    ]);

    F::Shutdown($Call);

    exit(0); //$Call['Return Code']);