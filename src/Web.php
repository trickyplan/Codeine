<?php

    /**
     * @author BreathLess
     * @date 27.28.11
     * @time 5:17
     */

    include 'Codeine/Core.php';

    F::Bootstrap();

    $Call = F::Run(
            'System.Interface.Web',
            'Run',
                array(
                     'Service' => 'Code.Flow.Front',
                     'Method'  => 'Run'
                )
        );

