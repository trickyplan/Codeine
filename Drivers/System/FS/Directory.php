<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Create', function ($Call)
        {
            return mkdir ($Call['Value']);
        });

    self::Fn ('Change', function ($Call)
        {
            return chdir($Call['Value']);
        });