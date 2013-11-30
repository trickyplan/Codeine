<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Write', function ($Call)
    {
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

        return $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

        if (isset($Call['Node']['Options'][$Call['Value']]))
            return $Call['Node']['Options'][$Call['Value']];
        else
            return null;
    });

    setFn('Where', function ($Call)
    {
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

        if (isset($Call['Node']['Options']))
            return (int) array_search($Call['Value'], $Call['Node']['Options']);
        else
            return (int) $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return array_rand(F::Live($Call['Node']['Options']));
    });