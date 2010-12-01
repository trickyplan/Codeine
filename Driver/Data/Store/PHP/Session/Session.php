<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: SESSION Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:25
     */

    self::Fn('Connect', function ($Call)
    {
        return session_start();
    });

    self::Fn('Disconnect', function ($Call)
    {
        return session_destroy();
    });

    self::Fn('Read', function ($Call)
    {
        if (isset($_SESSION[$Call['Data']['Where']['ID']]))
            return $_SESSION[$Call['Data']['Where']['ID']];
        else
            return null;
    });

    self::Fn('Create', function ($Call)
    {
        return $_SESSION[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Update', function ($Call)
    {
        return $_SESSION[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Delete', function ($Call)
    {
        if (isset($_SESSION[$Call['Data']['Where']['ID']]))
        {
            unset ($_SESSION[$Call['Data']['Where']['ID']]);
            return true;
        }
        else
            return null;
    });