<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Create', function ($Call)
        {
            return mkdir ($Call['Value']);
        });

    self::setFn ('Change', function ($Call)
        {
            return chdir($Call['Value']);
        });