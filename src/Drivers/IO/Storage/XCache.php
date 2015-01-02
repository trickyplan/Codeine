<?php

    /* Codeine
     * @author BreathLess
     * @description XCache Data Driver
     * @package Codeine
     * @version 8.x
     */

    setFn ('Open', function ($Call)
    {
        return extension_loaded('xcache');
    });

    setFn ('Read', function ($Call)
    {
        return xcache_get($Call['Scope'].$Call['Where']['ID']);
    });

    setFn ('Write', function ($Call)
    {
        return (null === $Call['Data'])?
            xcache_unset($Call['Scope'].$Call['Where']['ID']):
            xcache_set($Call['Scope'].$Call['Where']['ID'], $Call['Data'], $Call['TTL']);
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Execute', function ($Call)
    {
        return true;
    });

    setFn ('Exist', function ($Call)
    {
        return xcache_isset ($Call['Scope'].$Call['Where']['ID']);
    });