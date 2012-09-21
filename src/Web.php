<?php

    /**
     * @author BreathLess
     * @date 27.28.11
     * @time 5:17
     */


    if (file_exists(Root.'/down') && !isset($_COOKIE['Magic']))
    {
        readfile(__DIR__.'/down.html');
        die();
    }

    include 'Codeine/Core.php';

    F::Bootstrap (array(
                       'Path' => array(Root)
                       //,'Trace' => true
                  ));

    F::Run(
        'System.Interface.Web',
        'Run',
        [
            'Service' => 'Code.Flow.Front',
            'Method'  => 'Run'
        ]
    );

