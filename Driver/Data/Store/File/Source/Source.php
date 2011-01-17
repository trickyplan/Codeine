<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Codeine Source Store
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 14:13
     */

    self::Fn('Connect', function ($Call)
    {
        return Engine.Data::Path('Drivers');
    });

    self::Fn('Disconnect', function ($Call)
    {
        return true;
    });

    self::Fn('Read', function ($Call)
    {
        return file_get_contents($Call['Store']['Point']['DSN'].'/'.$Call['Data']['Where']['ID']);
    });

    self::Fn('Create', function ($Call)
    {
        return file_put_contents($Call['Store']['Point']['DSN'].$Call['ID'], $Call['Body']);
    });

    self::Fn('Update', function ($Call)
    {
        return file_put_contents($Call['Store']['Point']['DSN'].$Call['ID'], $Call['Body']);
    });

    self::Fn('Delete', function ($Call)
    {
        return unlink($Call['Store']['Point']['DSN'].$Call['ID']);
    });