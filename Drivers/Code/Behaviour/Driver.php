<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('beforeRun', function ($Call)
        {
            return $Call['Value'];
        });

    self::Fn ('afterRun', function ($Call)
        {

            return $Call['Value'];
        });