<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Condition contract checker
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 30.10.10
     * @time 5:33
     */


    self::Fn('Check', function ($Call)
    {
        if (!isset($Call['Contract']['Condition']))
            return true;
        else
            return Code::Run(
                array('F' => 'Conditions/Check',
                      'D' => $Call['Contract']['Condition'],
                      'Value' => $Call['Data']));
    });