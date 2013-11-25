<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::_loadSource('Entity.Control');

    setFn('Execute', function ($Call)
    {
        return F::Run('Order.Execute', 'Do', $Call, ['Entity' => 'Order']);
    });