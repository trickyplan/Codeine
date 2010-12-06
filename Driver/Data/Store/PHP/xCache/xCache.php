<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: xCache Store
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:33
     */

    self::Fn('Connect', function ($Call)
    {
        return extension_loaded('xcache');
    });

    self::Fn('Disconnect', function ($Call)
    {
        return extension_loaded('xcache');
    });

    self::Fn('Read', function ($Call)
    {
        if (!($Data = xcache_get($Call['Data']['Where']['ID'])))
            $Data = null;
        return $Data;
    });

    self::Fn('Create', function ($Call)
    {
        return xcache_set ($Call['Data']['ID'], $Call['Data']['Data']);
    });

    self::Fn('Update', function ($Call)
    {
        return xcache_set ($Call['Data']['ID'], $Call['Data']['Data']);
    });

    self::Fn('Delete', function ($Call)
    {
        return xcache_unset ($Call['Data']['Where']['ID']);
    });

    self::Fn('Exist', function ($Call)
    {
        return xcache_isset ($Call['Data']['Where']['ID']);
    });

    self::Fn('Inc', function ($Call)
    {
        return xcache_inc ($Call['Data']['Where']['ID'],$Call['Data']['Inc']);
    });