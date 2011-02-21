<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Input Select Strategy
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 18.11.10
     * @time 1:40
     */

    self::Fn('Select', function ($Call)
    {
        $Interfaces = Code::Conf('Inputs');

        foreach ($Interfaces as $Interface)
        {
            if (Code::Run(array(
                               'N'=>'System.Input.'.$Interface,
                               'F' => 'Detect')))
                break;
        }
        
        return $Interface.'/'.$Interface;
    });
