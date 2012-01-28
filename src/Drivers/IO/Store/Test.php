<?php

  /* Codeine
     * @author BreathLess
     * @description Test Driver for Data Engine 
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Open', function ($Call)
    {
        return 'Pong!';
    });

    self::setFn ('Read', function ($Call)
    {
        return $Call['Link'];
    });

    self::setFn ('Write', function ($Call)
    {
        return $Call['Data'];
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });