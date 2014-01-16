<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Script = F::loadOptions($Call['Call'], null, [], 'Scripts');

        $VCall = $Call;
        $VCall['Environment'] = F::Environment();

        foreach ($Script as $Run)
            F::Live($Run, $VCall);

        return $Call;
    });