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

        if (isset($Call['Renderer']) && $Call['Renderer']=='View.HTML')
            $Call['Renderer'] = 'View.Plaintext';

        $Call = F::Run('View', 'Render', $Call);

        echo $Call['Output']."\n";

        return $Call;
    });