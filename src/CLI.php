<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    include 'Codeine/Core.php';

    foreach ($argv as $arg)
        if (preg_match('/--(\S+)\=(\S+)/', $arg, $Pockets))
            $Opts[$Pockets[1]] = $Pockets[2];

    if (!isset($Opts['Method']))
        $Opts['Method'] = 'Do';

    !defined('Root')? define('Root', Codeine): false;

    $Call = F::Bootstrap
    ([
        'Path' => [Root],
        'Environment' => isset($Opts['Environment'])? $Opts['Environment']: 'Production',
        'Service' => 'System.Interface.CLI',
        'Method' => 'Run',
        'Call' => $Opts
    ]);

    exit(0);