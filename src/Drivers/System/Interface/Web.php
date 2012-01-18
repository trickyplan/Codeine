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
        header ('Content-type: text/html; charset=utf-8');

        // $Call['Server'] = $_SERVER;
        // $Call['Request'] = $_REQUEST;

        $Call['Value'] = $_SERVER['REQUEST_URI'];

        $Call = F::Run($Call['Service'], $Call['Method'], $Call);

        //foreach ($Call['Headers'] as $Key => $Value)
        //    header ($Key . ': ' . $Value);

        echo $Call['Output'];

        ob_flush ();
        return $Call;
    });