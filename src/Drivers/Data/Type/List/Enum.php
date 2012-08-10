<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn ('Write', function ($Call)
    {
        return array_search($Call['Value'], $Call['Node']['Options']) ;
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