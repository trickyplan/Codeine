<?php

    /* Codeine
     * @author BreathLess
     * @description Search Date
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call['Engines'][$Call['Engine']], $Call);
    });

    setFn('Add', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call['Engines'][$Call['Engine']], $Call);
    });

    setFn('Remove', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call['Engines'][$Call['Engine']], $Call);
    });

    setFn('Query', function ($Call)
    {
        return F::Run($Call['Engines'][$Call['Engine']]['Driver'], null, $Call['Engines'][$Call['Engine']], $Call);
    });


