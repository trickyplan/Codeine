<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: POST Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:25
     */

    self::Fn('Connect', function ($Call)
    {
        return isset($_POST);
    });

    self::Fn('Disconnect', function ($Call)
    {
        return isset($_POST);
    });

    self::Fn('Read', function ($Call)
    {
        if (isset($_POST[$Call['Data']['Where']['ID']]))
            return $_POST[$Call['Data']['Where']['ID']];
        else
            return null;
    });

    self::Fn('Create', function ($Call)
    {
        return $_POST[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Update', function ($Call)
    {
        return $_POST[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Delete', function ($Call)
    {
        if (isset($_POST[$Call['Data']['Where']['ID']]))
        {
            unset ($_POST[$Call['Data']['Where']['ID']]);
            return true;
        }
        else
            return null;
    });