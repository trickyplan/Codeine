<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: REQUEST Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:25
     */

    self::Fn('Connect', function ($Call)
    {
        return isset($_REQUEST);
    });

    self::Fn('Disconnect', function ($Call)
    {
        return isset($_REQUEST);
    });

    self::Fn('Read', function ($Call)
    {
        if (isset($_REQUEST[$Call['Data']['Where']['ID']]))
            return $_REQUEST[$Call['Data']['Where']['ID']];
        else
            return null;
    });

    self::Fn('Create', function ($Call)
    {
        return $_REQUEST[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Update', function ($Call)
    {
        return $_REQUEST[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Delete', function ($Call)
    {
        if (isset($_REQUEST[$Call['Data']['Where']['ID']]))
        {
            unset ($_REQUEST[$Call['Data']['Where']['ID']]);
            return true;
        }
        else
            return null;
    });