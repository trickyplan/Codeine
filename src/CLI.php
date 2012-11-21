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

    F::Bootstrap ([
                       'Path' => array(Root),
                       'Environment' => isset($Opts['Environment'])? $Opts['Environment']: 'Production'
                  ]);

    F::Run(
        'System.Interface.CLI',
        'Run',
        $Opts
    );

    exit(0);