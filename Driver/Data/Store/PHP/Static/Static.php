<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Static Variable Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 15.11.10
     * @time 21:50
     */

    self::Fn('Connect', function ($Call)
    {
        if (isset($Call['Point']['DSN']))
            return $Call['Point']['DSN'];
        else
            return 'Default';
    });


    self::Fn('Disconnect', function ($Call)
    {
        return true;
    });


    self::Fn('Read', function ($Call)
    {
        return Data::Pull($Call['Where']['ID']);
    });

    self::Fn('Create', function ($Call)
    {
        return Data::Push($Call['ID'], $Call['Data']);
    });

    self::Fn('Update', function ($Call)
    {
        return Data::Push($Call['ID'], $Call['Data']);
    });

    self::Fn('Delete', function ($Call)
    {
        Data::Push($Call['ID'], null);
    });


