<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Script = F::loadOptions($Call['Run'], null, [], 'Scripts');

        $VCall = $Call;
        $VCall['Environment'] = F::Environment();

        foreach ($Script as $Run)
            $VCall = F::Live($Run, $VCall);

        $Call['Output']['Content'] = $VCall;

        return $Call;
    });