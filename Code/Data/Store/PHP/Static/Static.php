<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Static Variable Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 15.11.10
     * @time 21:50
     */

    $Connect = function ($Call)
    {
        if (isset($Call['Point']['DSN']))
            return $Call['Point']['DSN'];
        else
            return 'Default';
    };

    $Disconnect = function ($Call)
    {
        return true;
    };

    $Read = function ($Call)
    {
        if (isset(Data::$Data[$Call['Point']][$Call['Data']['Key']]))
            return Data::$Data[$Call['Point']][$Call['Data']['Key']];
        else
            return null;
    };

    $Create = function ($Call)
    {
        Data::$Data[$Call['Point']][$Call['Data']['Key']] = $Call['Data']['Value'];
        return true;
    };

    $Update = function ($Call)
    {
        if (isset(Data::$Data[$Call['Point']][$Call['Old']['Key']]))
        {
            Data::$Data[$Call['Point']][$Call['Old']['Key']] = $Call['New']['Value'];
            return true;
        }
        else
            return false;
    };

    $Delete = function ($Call)
    {
        if (isset(Data::$Data[$Call['Point']][$Call['Data']['Key']]))
            unset(Data::$Data[$Call['Point']][$Call['Data']['Key']]);
        return !isset(Data::$Data[$Call['Point']][$Call['Data']['Key']]);
    };


