<?php

    /* Codeine
     * @author BreathLess
     * @description APC Data Driver 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Open', function ($Call)
    {
        return extension_loaded('apc');
    });

    setFn ('Read', function ($Call)
    {
        return apc_fetch($Call['Where']['ID']);
    });

    setFn ('Write', function ($Call)
    {
        return null === $Call['Data']?
            apc_delete($Call['Where']['ID']):
            apc_store($Call['Where']['ID'], $Call['Data'], $Call['TTL']);
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Execute', function ($Call)
    {
        return true;
    });