<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Run', function ($Call)
    {
        $Call = F::Hook('beforeInterfaceRun', $Call);

        $Call['Return Code'];
            if (!isset($Call['Skip Run']))
                $Call = F::Apply($Call['Service'], $Call['Method'], $Call);

        $Call = F::Hook('afterInterfaceRun', $Call);

        if (isset($Call['Output']))
            $Call = F::Live ($Call['Interface']['Output'], $Call, ['Data' => $Call['Output']]);

        if (isset($Call['Failure']) && $Call['Failure'])
            $Call['Return Code'] = 1;

        return $Call;
    });