<?php

    /**
     * @author BreathLess
     * @date 27.08.11
     * @time 5:17
     */

    include 'codeine/Core.php';

    F::Bootstrap();

    F::Run(
        'System.Interface.Web',
        'Run',
            array(
                 'Service' => 'Code.Flow.Front',
                 'Method'  => 'Run'
            )
    );

