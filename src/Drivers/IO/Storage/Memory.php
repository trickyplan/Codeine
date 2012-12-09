<?php

    /* Codeine
     * @author BreathLess
     * @description APC Data Driver 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Open', function ($Call)
    {
        return true;
    });

    setFn ('Read', function ($Call)
    {
        return F::Get($Call['Where']['ID']);
    });

    setFn ('Write', function ($Call)
    {
        return F::Set($Call['Where']['ID'], $Call['Data'], $Call['TTL']);
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Execute', function ($Call)
    {
        return true;
    });