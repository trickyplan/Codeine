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

        return isset($Call['Node']['Options'][$Call['Value']])? $Call['Value']
                : array_search($Call['Value'], $Call['Node']['Options']);
    });

    setFn('Read', function ($Call)
    {
        if (!isset($Call['Purpose']) || $Call['Purpose'] != 'Where')
        {
            $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

            return isset($Call['Node']['Options'][$Call['Value']])? $Call['Node']['Options'][$Call['Value']]: $Call['Value'];
        }
        else
            return $Call['Value'];

    });

    setFn('Populate', function ($Call)
    {
        return array_rand($Call['Node']['Options']);
    });