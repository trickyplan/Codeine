<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: eAccelerator Store
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 21:21
     */

    self::Fn('Connect', function ($Call)
    {
        return extension_loaded('eaccelerator');
    });

    self::Fn('Disconnect', function ($Call)
    {
        return extension_loaded('eaccelerator');
    });

    self::Fn('Read', function ($Call)
    {
        return unserialize(eaccelerator_get($Call['Data']['Where']['ID']));
    });

    self::Fn('Create', function ($Call)
    {
        return eaccelerator_put ($Call['Data']['ID'], serialize($Call['Data']['Data']));
    });

    self::Fn('Update', function ($Call)
    {
        return eaccelerator_put ($Call['Data']['ID'], serialize($Call['Data']['Data']));
    });

    self::Fn('Delete', function ($Call)
    {
        return eaccelerator_rm ($Call['Data']['Where']['ID']);
    });