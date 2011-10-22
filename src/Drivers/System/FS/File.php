<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Touch', function ($Call)
    {
        return touch($Call['Filename']);
    });

    self::Fn ('Create', function ($Call)
        {
            return file_put_contents($Call['Filename'], F::ifCall($Call['Value']));
        });

    self::Fn ('Copy', function ($Call)
        {
            return copy ($Call['From'], $Call['To']);
        });