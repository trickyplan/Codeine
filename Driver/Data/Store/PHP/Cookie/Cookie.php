<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: COOKIE Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:25
     */

    self::Fn('Connect', function ($Call)
    {
        return isset($_COOKIE);
    });

    self::Fn('Disconnect', function ($Call)
    {
        return isset($_COOKIE);
    });

    self::Fn('Read', function ($Call)
    {
        if (isset($_COOKIE[$Call['Data']['Where']['ID']]))
            return $_COOKIE[$Call['Data']['Where']['ID']];
        else
            return null;
    });

    self::Fn('Create', function ($Call)
    {
        return setcookie($Call['Data']['ID'], $Call['Data']['Data'], 0,'/',Host, false, true);
    });

    self::Fn('Update', function ($Call)
    {
        return setcookie($Call['Data']['ID'], $Call['Data']['Data'], 0,'/',Host, false, true);
    });

    self::Fn('Delete', function ($Call)
    {
        if (isset($_COOKIE[$Call['Data']['Where']['ID']]))
        {
            return setcookie($Call['Data']['ID'], null, 0,'/',Host, false, true);
            return true;
        }
        else
            return null;
    });