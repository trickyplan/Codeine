<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('LatLon', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'LatLon', $Call);
        else
            return null;
    });

    self::setFn('Country', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'Country', $Call);
        else
            return null;
    });

    self::setFn('Region', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'Region', $Call);
        else
            return null;
    });


    self::setFn('City', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'City', $Call);
        else
            return null;
    });
