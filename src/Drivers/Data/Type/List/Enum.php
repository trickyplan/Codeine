<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Write', function ($Call)
    {
        return array_search($Call['Value'], $Call['Node']['Enum']);
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Node']['Enum'][$Call['Value']];
    });