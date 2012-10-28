<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Add', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });

    self::setFn('Substract', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });

    self::setFn('Multiply', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });

    self::setFn('Divide', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });