<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Test Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 13:38
     */

    self::Fn('Test', function ($Call)
    {
        $Call['Items'] = array();
        
        $Call['Items'][] = array(
            'UI'        => 'Badge',
            'Data'      => Code::Run(
                array(
                    'N' => 'System.Hello.World',
                    'F' => 'Print'
                )
            ));

        return $Call;
    });
