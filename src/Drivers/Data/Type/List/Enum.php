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
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);
        return $Call['Node']['Options'][(int) $Call['Value']];
    });

    self::setFn('Populate', function ($Call)
    {
        return array_rand($Call['Node']['Options']);
    });