<?php

    /* Codeine
     * @author BreathLess
     * @description APC Data Driver 
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Open', function ($Call)
    {
        return extension_loaded('apc');
    });

    self::setFn ('Read', function ($Call)
    {
        return apc_fetch($Call['Where']['ID']);
    });

    self::setFn ('Write', function ($Call)
    {
        return null === $Call['Data']?
            apc_delete($Call['Where']['ID']):
            apc_store($Call['Where']['ID'], $Call['Data'], $Call['TTL']);
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });