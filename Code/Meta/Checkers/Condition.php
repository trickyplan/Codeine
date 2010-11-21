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

    $Check = function ($Args)
    {
        if (!isset($Args['Contract']['Condition']))
            return true;
        else
            return Code::Run(
                array('F'=>'Conditions/Check',
                      'D'=>$Args['Contract']['Condition'],
                      'Value'=>$Args['Data']));
    };