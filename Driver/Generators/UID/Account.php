<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Bank Account Number
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 15:01
     */

    self::Fn('Get', function ($Call)
    {
        $Output = '';
        
        for ($ic = 0; $ic < 20; $ic++)
            $Output.= rand(0,9);

        return $Output;
    });