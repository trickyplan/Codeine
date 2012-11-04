<?php

    /* Codeine
     * @author BreathLess
     * @description Search Engine 
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call);
    });

    setFn('Add', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call);
    });

    setFn('Remove', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call);
    });

    setFn('Query', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], 'Query', $Call);
    });


