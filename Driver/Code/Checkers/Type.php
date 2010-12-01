<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Type contract checker
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 30.10.10
     * @time 5:33
     */


    self::Fn('Check', function ($Call)
    {
        if (!isset($Call['Contract']['Type']))
            return true;
        else
            return Code::Run(
                array( 'F' => 'Data/Types::Validate',
                       'D' => $Call['Contract']['Type'],
                       'Value' => $Call['Data']));
    });