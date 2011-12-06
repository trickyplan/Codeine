<?php

  /* Codeine
     * @author BreathLess
     * @description Test Driver for Data Engine 
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Open', function ($Call)
    {
        return true;
    });

    self::setFn ('Read', function ($Call)
    {
        return 'Pong!';
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