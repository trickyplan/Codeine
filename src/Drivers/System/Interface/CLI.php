<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn ('Run', function ($Call)
    {
        $Call = F::Run ($Call['Service'], $Call['Method'], $Call);

        $Call['Renderer'] = 'View.Plaintext';

        $Call = F::Run('View', 'Render', $Call);

        echo $Call['Output']."\n";

        return $Call;
    });