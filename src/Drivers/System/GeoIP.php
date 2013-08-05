<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('LatLon', function ($Call)
    {
        $Call['IP'] = F::Live($Call['IP']);

        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'LatLon', $Call);
        else
            return null;
    });

    setFn('Country', function ($Call)
    {
        $Call['IP'] = F::Live($Call['IP']);

        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'Country', $Call);
        else
            return null;
    });

    setFn('Region', function ($Call)
    {
        $Call['IP'] = F::Live($Call['IP']);

        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'Region', $Call);
        else
            return null;
    });


    setFn('City', function ($Call)
    {
        $Call['IP'] = F::Live($Call['IP']);

        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], 'City', $Call);
        else
            return null;
    });
