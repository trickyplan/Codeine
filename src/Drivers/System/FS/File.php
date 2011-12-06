<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Touch', function ($Call)
    {
        return touch($Call['Filename']);
    });

    self::setFn ('Create', function ($Call)
        {
            return file_put_contents($Call['Filename'], F::ifCall($Call['Value']));
        });

    self::setFn ('Copy', function ($Call)
        {
            return copy ($Call['From'], $Call['To']);
        });