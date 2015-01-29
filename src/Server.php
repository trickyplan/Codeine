<?php

    /**
     * @author bergstein@trickyplan.com
     * @time 5:17
     */

    include 'Core.php';

    foreach ($argv as $arg)
        if (preg_match('/--(\S+)\=(\S+)/', $arg, $Pockets))
            $Opts[$Pockets[1]] = $Pockets[2];

    if (!isset($Opts['Method']))
        $Opts['Method'] = 'Do';

    !defined('Root')? define('Root', Codeine): false;

    $Call = F::Bootstrap
    ([
        'Paths' => [Root],
        'Environment' => isset($Opts['Environment'])? $Opts['Environment']: 'Production',
        'Service' => 'System.Interface.Server',
        'Method' => 'Do',
        'Call' => $Opts
    ]);

    F::Shutdown($Call);

    exit($Call['Return Code']);