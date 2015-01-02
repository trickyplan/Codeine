<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    self::_loadSource('Entity.Control');

    setFn('Order', function ($Call)
    {
        return $Call;
    });