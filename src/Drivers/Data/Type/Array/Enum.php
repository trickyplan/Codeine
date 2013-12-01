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

        $Data = [];


        foreach ($Call['Value'] as &$Value)
            $Data[] = (int) array_search($Value, $Call['Node']['Options']);

        return $Data;
    });

    setFn('Read', function ($Call)
    {
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

        if(is_array($Call['Value']))
            foreach ($Call['Value'] as &$Value)
                if (isset($Call['Node']['Options'][(int)$Value]))
                    $Value = $Call['Node']['Options'][(int)$Value];

        return $Call['Value'];
    });

    setFn('Where', function ($Call)
    {
        $Call['Node']['Options'] = F::Live($Call['Node']['Options']);

        $Data = [];

        foreach ($Call['Value'] as &$Value)
            $Data[] = (int) array_search($Value, $Call['Node']['Options']);

        return $Data;
    });

    setFn('Populate', function ($Call)
    {
        return [array_rand(F::Live($Call['Node']['Options']))];
    });