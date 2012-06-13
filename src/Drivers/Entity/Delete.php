<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Purpose'] = 'Delete';
        $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDeleteGet', $Call);

        F::Run('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeleteGet', $Call);

        return $Call;
    });