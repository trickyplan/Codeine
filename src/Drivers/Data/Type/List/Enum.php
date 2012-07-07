<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Write', function ($Call)
    {
        return (int) $Call['Value'];
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Node']['Options'][(int) $Call['Value']];
    });