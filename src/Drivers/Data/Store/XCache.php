<?php

    /* Codeine
     * @author BreathLess
     * @description XCache Data Driver
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Open', function ($Call)
    {
        return extension_loaded('xcache');
    });

    self::setFn ('Read', function ($Call)
    {
        return xcache_get($Call['Where']['ID']);
    });

    self::setFn ('Write', function ($Call)
    {
        return (null === $Call['Data'])?
            xcache_unset($Call['Where']['ID']):
            xcache_set($Call['Where']['ID'], $Call['Data'], $Call['TTL']);
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });