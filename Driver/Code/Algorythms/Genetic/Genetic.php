<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: GA implementation (PoC)
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 14:13
     */

    self::Fn('Run', function ($Call)
    {
        // Initialization
        
        foreach ($Call['Sets'] as $Index => $Set)
        {
            $Ranks[$Index] =  Code::Run(
                array_merge($Call['Ranker'],
                    array('Input' =>
                        array_merge($Call['Base'], $Set)))
            );
        }

        // Crossover
    });