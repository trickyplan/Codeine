<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Type contract checker
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 30.10.10
     * @time 5:33
     */

    $Check = function ($Args)
    {
        if (!isset($Args['Contract']['Type']))
            return true;
        else
            return Code::Run(
                array('F'=>'Data/Types/Validate','D'=>$Args['Contract']['Type'], 'Value' => $Args['Data']));
    };