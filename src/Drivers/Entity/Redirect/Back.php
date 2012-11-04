<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Store', function ($Call)
    {
        if(!isset($Call['BackURL']))
            $Call['BackURL'] = $_SERVER['HTTP_REFERER'];
        return $Call;
    });

    setFn('Redirect', function ($Call)
    {
        $Call = F::Run('System.Interface.Web', 'Redirect', $Call, array('Location' => $Call['Request']['BackURL']));
        return $Call;
    });

    setFn('Restore', function ($Call)
    {
        $Call['BackURL'] = $Call['Request']['BackURL'];

        return $Call;
    });