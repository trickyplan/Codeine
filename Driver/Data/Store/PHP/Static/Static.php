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
        if (isset(Data::$Data[$Call['Point']][$Call['Where']['Key']]))
            return Data::$Data[$Call['Point']][$Call['Where']['Key']];
        else
            return null;
    });

    self::Fn('Create', function ($Call)
    {
        Data::$Data[$Call['Point']][$Call['Key']] = $Call['Value'];
        return true;
    });

    self::Fn('Update', function ($Call)
    {
        if (isset(Data::$Data[$Call['Point']][$Call['Old']['Key']]))
            {
                Data::$Data[$Call['Point']][$Call['Old']['Key']] = $Call['New']['Value'];
                return true;
            }
            else
                return false;
    });

    self::Fn('Delete', function ($Call)
    {
        if (isset(Data::$Data[$Call['Point']][$Call['Key']]))
            unset(Data::$Data[$Call['Point']][$Call['Key']]);
        return !isset(Data::$Data[$Call['Point']][$Call['Key']]);
    });


