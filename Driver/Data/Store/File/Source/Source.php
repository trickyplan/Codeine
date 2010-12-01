<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Codeine Source Store
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 01.12.10
     * @time 14:13
     */

    self::Fn('Connect', function ($Call)
    {
        return Data::Path('Drivers');
    });

    self::Fn('Disconnect', function ($Call)
    {
        return true;
    });

    self::Fn('Read', function ($Call)
    {

    });

    self::Fn('Create', function ($Call)
    {

    });

    self::Fn('Update', function ($Call)
    {
        
    });

    self::Fn('Delete', function ($Call)
    {
        
    });