<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Simple Crossover for GA
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 14:21
     */

    self::Fn('Do', function ($Call)
    {
        $Call['Input'] = array(
            array(1,2),
            array(4,5,6)
        );

        $Data = array();

        foreach ($Call['Input'] as $IX1 => $L1)
            foreach ($Call['Input'] as $IX2 => $L2)
                foreach ($L1 as $K1 => $V1)
                {
                    $L2[$K1] = $V1;
                    $Data[implode($L2)] = $L2;
                }
        
        var_dump($Data);
        
        return $Data;

    });