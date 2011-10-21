<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple Crossover for GA
     * @package Codeine
     * @version 6.0
     * @date 06.12.10
     * @time 14:21
     */

    self::Fn('Do', function ($Call)
    {
        $Call['Value'] = array(
            array(1,2),
            array(4,5,6)
        );

        $Data = array();

        foreach ($Call['Value'] as $IX1 => $L1)
            foreach ($Call['Value'] as $IX2 => $L2)
                foreach ($L1 as $K1 => $V1)
                {
                    $L2[$K1] = $V1;
                    $Data[implode($L2)] = $L2;
                }
                
        return $Data;

    });
