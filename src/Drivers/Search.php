<?php

    /* Codeine
     * @author BreathLess
     * @description Search Engine 
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Open', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call);
    });

    self::setFn('Add', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call);
    });

    self::setFn('Remove', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call);
    });

    self::setFn('Query', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], 'Query', $Call);
    });


