<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Add', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });

    setFn('Substract', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });

    setFn('Multiply', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });

    setFn('Divide', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });