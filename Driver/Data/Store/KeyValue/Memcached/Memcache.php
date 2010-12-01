<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Memcache Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:12
     */

    self::Fn('Connect', function ($Call)
    {
        $Memcached = new Memcache();

        if (!$Memcached->connect($Call['Host'], $Call['Port']))
        {
            Code::On(__CLASS__, 'errDataMemCachedCannotConnect', $Call);
            return false;
        }
        else
            return $Memcached;
    });

    self::Fn('Disconnect', function ($Call)
    {
        if (isset($Call['Store']))
            return $Call['Store']->close();
        else
            Code::On(__CLASS__, 'errDataMemcachedStoreNotFoundAtDisconnect', $Call);
    });

    self::Fn('Read', function ($Call)
    {
        if ($Call['Store'])
            return $Call['Store']->get($Call['Point']['Scope'].$Call['Data']['Where']['ID']);
        else
            Code::On(__CLASS__, 'errDataMemcachedStoreNotFoundAtRead', $Call);
    });

    self::Fn('Create', function ($Call)
    {
        if ($Call['Store'])
            return $Call['Store']->add($Call['Point']['Scope'].$Call['Data']['Where']['ID'], $Call['Data']);
        else
            Code::On(__CLASS__, 'errDataMemcachedStoreNotFoundAtCreate', $Call);
    });

    self::Fn('Update', function ($Call)
    {
        if ($Call['Store'])
            return $Call['Store']->set($Call['Point']['Scope'].$Call['Data']['Where']['ID'], $Call['Data']);
        else
            Code::On(__CLASS__, 'errDataMemcachedStoreNotFoundAtUpdate', $Call);
    });

    self::Fn('Delete', function ($Call)
    {
        if ($Call['Store'])
            return $Call['Store']->delete($Call['Point']['Scope'].$Call['Data']['Where']['ID']);
        else
            Code::On(__CLASS__, 'errDataMemcachedStoreNotFoundAtDelete', $Call);
    });