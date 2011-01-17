<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Natural Language Routing
     * @package Codeine
     * @subpackage Drivers
     * @version 0.0
     * @date 14.11.10
     * @time 15:45
     */

    self::Fn('Route', function ($Call)
    {
        return  Code::Run(
            array('N'=>'Recognition.Language','F'=>'Recognize', 'Value' => $Call['Call'])
        );
    });