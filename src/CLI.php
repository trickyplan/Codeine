<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    include 'Codeine/Core.php';

    F::Bootstrap (array(
                       'Path' => array(Root)
                       //,'Trace' => true
                  ));

    $Opts = getopt ('', array('Service:', 'Method:'));

    F::Run(
        'System.Interface.CLI',
        'Run',
        $Opts
    );

    exit(0);