<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Touch', function ($Call)
    {
        return touch($Call['Filename']);
    });

    self::setFn ('Create', function ($Call)
        {
            return file_put_contents($Call['Filename'], F::Live($Call['Value']));
        });

    self::setFn ('Copy', function ($Call)
        {
            return copy ($Call['From'], $Call['To']);
        });