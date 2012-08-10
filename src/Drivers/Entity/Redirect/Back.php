<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Store', function ($Call)
    {
        if(!isset($Call['BackURL']))
            $Call['BackURL'] = $_SERVER['HTTP_REFERER'];
        return $Call;
    });

    self::setFn('Redirect', function ($Call)
    {
        $Call = F::Run('System.Interface.Web', 'Redirect', $Call, array('Location' => $Call['Request']['BackURL']));
        return $Call;
    });

    self::setFn('Restore', function ($Call)
    {
        $Call['BackURL'] = $Call['Request']['BackURL'];

        return $Call;
    });