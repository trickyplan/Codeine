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

        return (isset($Call['Node']['Options'][$Call['Value']]))?
            (int) $Call['Value']:
            (int) array_search($Call['Value'], $Call['Node']['Options']);
    });

    setFn('Read', function ($Call)
    {
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

        return
            (isset($Call['Node']['Options'][$Call['Value']])?
                $Call['Node']['Options'][$Call['Value']]
                : (int) $Call['Value']);
    });

    setFn('Where', function ($Call)
    {
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

        if (isset($Call['Node']['Options']))
            return array_search($Call['Value'], $Call['Node']['Options']);
        else
            return $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return array_rand($Call['Node']['Options']);
    });