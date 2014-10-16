<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::_loadSource('Entity.Control');

    setFn('Login', function ($Call)
    {
        return F::Run('User.Login', 'Do', $Call);
    });

    setFn('Rights', function ($Call)
    {
        return F::Run('User.Rights', 'Do', $Call);
    });