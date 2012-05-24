<?php

  /* Codeine
     * @author BreathLess
     * @description Test Driver for Data Engine 
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Open', function ($Call)
    {
    });

    self::setFn ('Read', function ($Call)
    {
        d(__FILE__, __LINE__, $Call['Where']);
    });

    self::setFn ('Write', function ($Call)
    {
        d(__FILE__, __LINE__, $Call['Data']);
    });

    self::setFn ('Close', function ($Call)
    {
        d(__FILE__, __LINE__, $Call);
    });

    self::setFn ('Execute', function ($Call)
    {

    });