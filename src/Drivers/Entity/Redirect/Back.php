<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Store', function ($Call)
    {
        if(!isset($Call['Request']['BackURL']) && isset($_SERVER['HTTP_REFERER']))
            $Call['Request']['BackURL'] = $_SERVER['HTTP_REFERER'];

        return $Call;
    });

    setFn('Restore', function ($Call)
    {
        if (isset($Call['Request']['BackURL']))
            $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => $Call['Request']['BackURL']]);
        else
            if (isset($_SERVER['HTTP_REFERER']))
                $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => $_SERVER['HTTP_REFERER']]);

        return $Call;
    });