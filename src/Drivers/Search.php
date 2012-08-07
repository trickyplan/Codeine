<?php

    /* Codeine
     * @author BreathLess
     * @description Search Engine 
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Open', function ($Call)
    {
        // TODO Realize "Open" function


        return $Call;
    });

    self::setFn('Add', function ($Call)
    {
        // TODO Realize "Add" function


         return $Call;
    });

    self::setFn('Remove', function ($Call)
    {
        // TODO Realize "Remove" function


        return $Call;
    });

    self::setFn('Query', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], 'Query', $Call);
    });