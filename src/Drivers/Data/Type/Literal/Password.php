<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Write', function ($Call)
    {
        if (!empty($Call['Value']) && $Call['Purpose'] != 'Update')
            return F::Run('Security.Auth.System.Password', 'Challenge', $Call);
        else
            return null;
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });