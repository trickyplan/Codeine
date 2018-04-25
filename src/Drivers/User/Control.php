<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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
    
    setFn('Impersonate', function ($Call)
    {
        return F::Run('User.Impersonate', 'Do', $Call);
    });