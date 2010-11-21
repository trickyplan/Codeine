<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Interface Select Strategy
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 18.11.10
     * @time 1:40
     */

    $Select = function ($Call)
    {
        $Interfaces = Code::GetInterfaces();
        foreach ($Interfaces as $Interface)
        {
            if (Code::Run(array('F'=>'System/Interface/'.$Interface.'/Detect')))
                break;
        }
        return $Interface.'/'.$Interface;
    };