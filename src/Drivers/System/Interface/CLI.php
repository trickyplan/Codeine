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

        if (!isset($Call['Skip Run']))
            $Call = F::Run($Call['Service'], $Call['Method'], $Call);

        $Call = F::Run('View', 'Render', $Call);

        if (isset($Call['Output']))
            $Call = F::Live ($Call['Interface']['Output'], ['Data' => $Call['Output']]);

        return $Call;
    });