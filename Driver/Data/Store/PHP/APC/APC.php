<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: APC Store
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:33
     */

    self::Fn('Connect', function ($Call)
    {
        return extension_loaded('apc');
    });

    self::Fn('Disconnect', function ($Call)
    {
        return extension_loaded('apc');
    });

    self::Fn('Read', function ($Call)
    {
        if (!($Data = apc_fetch($Call['Data']['Where']['ID'])))
            $Data = null;
        return $Data;
    });

    self::Fn('Create', function ($Call)
    {
        return apc_add ($Call['Data']['ID'], $Call['Data']['Data']);
    });

    self::Fn('Update', function ($Call)
    {
        return apc_store ($Call['Data']['ID'], $Call['Data']['Data']);
    });

    self::Fn('Delete', function ($Call)
    {
        return apc_delete ($Call['Data']['Where']['ID']);
    });