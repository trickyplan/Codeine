<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Condition contract checker
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 30.10.10
     * @time 5:33
     */


    self::Fn('Check', function ($Call)
    {
        if (!isset($Call['Contract']['Condition']))
            return true;
        else
            return Code::Run(
                array('N' => 'Conditions',
                      'F' => 'Check',
                      'D' => $Call['Contract']['Condition'],
                      'Value' => $Call['Data']));
    });