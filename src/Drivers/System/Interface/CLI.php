<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Run', function ($Call)
    {
        $Call = F::Run ($Call['Service'], $Call['Method'], $Call);

        $Call = F::Run('View', 'Render', $Call);

        echo 'Cli';
        echo $Call['Output']."\n";

        return $Call;
    });