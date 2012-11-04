<?php

  /* Codeine
     * @author BreathLess
     * @description Test Driver for Data Engine 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Open', function ($Call)
    {
        return true;
    });

    setFn ('Read', function ($Call)
    {
        return true;
    });

    setFn ('Write', function ($Call)
    {
        return true;
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Execute', function ($Call)
    {
        return true;
    });