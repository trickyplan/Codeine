<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Ratio = preg_match_all('/([<br\/?>])/m', $Call['Value'])/mb_strlen($Call['Value']);

        if ($Ratio > $Call['Redundant Newlines']['Max Ratio'])
            $Call['Value'] =  preg_replace('/([<br\/?>])/m', '', $Call['Value']);

        $Call['Value'] = preg_replace('/([\n]{3,})/m', PHP_EOL, $Call['Value']);
        $Call['Value'] = preg_replace('/([<br\/?>]{3,})/m', '<br/>', $Call['Value']);

        return $Call;
     });