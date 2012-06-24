<?php

  /* Codeine
     * @author BreathLess
     * @description Test Driver for Data Engine 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Open', function ($Call)
    {
        return true;
    });

    self::setFn ('Read', function ($Call)
    {
        return true;
    });

    self::setFn ('Write', function ($Call)
    {
        return true;
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });