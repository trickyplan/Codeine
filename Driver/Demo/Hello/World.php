<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Hello world, motherfucker!
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 5:56
     */

    self::Fn('Print', function ($Call)
    {
        $Call['Items'][] =
            array(
                'UI' => 'Badge',
                'Value'=> 'Hello, world!'
            );
            
        return $Call;
    });
