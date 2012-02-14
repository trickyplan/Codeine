<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Input', function ($Call)
    {
        return F::Live($Call['Value']);
    });