<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Create', function ($Call)
        {
            return mkdir ($Call['Value']);
        });

    self::setFn ('Change', function ($Call)
        {
            return chdir($Call['Value']);
        });