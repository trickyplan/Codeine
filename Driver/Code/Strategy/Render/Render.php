<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Render Selector
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 23:28
     */

    self::Fn('Select', function ($Call)
    {
        $ParentCall = Code::ParentCall();
        
        if (isset($ParentCall['Input']['As']))
            return $ParentCall['Input']['As'];
        else
            return 'Codeine';
    });