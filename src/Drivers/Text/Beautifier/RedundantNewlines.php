<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Value'] = preg_replace('/([\n]{3,})/m', PHP_EOL, $Call['Value']);
        $Call['Value'] = preg_replace('/([<br\/?>]{3,})/m', '<br/>', $Call['Value']);

        return $Call;
     });