<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    include 'Codeine/Core.php';

    $Opts = getopt ('', array('Service:', 'Method:', 'Environment:'));

    if (!isset($Opts['Method']))
        $Opts['Method'] = 'Do';

    F::Bootstrap ([
                       'Path' => array(Root),
                       'Environment' => isset($Opts['Environment'])? $Opts['Environment']: 'Production'
                       //,'Trace' => true
                  ]);

    F::Run(
        'System.Interface.CLI',
        'Run',
        $Opts
    );

    exit(0);