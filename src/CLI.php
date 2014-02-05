<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    include 'Core.php';

    foreach ($argv as $arg)
        if (preg_match('/^--(\w+)\=(.+)$/Ssu', $arg, $Pockets))
            $Opts[$Pockets[1]] = $Pockets[2];

    !defined('Root')? define('Root', getcwd()): false;

    if (empty($Opts))
        ;
    else
    {
        if (!isset($Opts['Method']))
            $Opts['Method'] = 'Do';

        $Call = F::Bootstrap
            ([
                'Paths' => [Root],
                'Environment' => isset($Opts['Environment'])? $Opts['Environment']: 'Production',
                'Service' => 'System.Interface.CLI',
                'Method' => 'Do',
                'Call' => $Opts
            ]);

        F::Shutdown($Call);
        exit($Call['Return Code']);
    }

