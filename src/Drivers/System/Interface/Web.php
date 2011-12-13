<?php

    /* Codeine
     * @author BreathLess
     * @description Web Interface 
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Run', function ($Call)
    {
        ob_start();

            $Call = F::Run($Call['Service'], $Call['Method'], $Call, $_REQUEST, $_SERVER);

            foreach ($Call['Headers'] as $Key => $Value)
                header ($Key . ': ' . $Value);

            echo $Call['Output'];

        ob_flush ();
        return $Call;
    });