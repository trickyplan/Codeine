<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Multirunner
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 1:01
     */

    self::Fn('Run', function ($Call)
    {
        $Result = array();

        if (is_array($Call['Call']))
            foreach ($Call['Call'] as $Index => $OneCall)
            {
                $OneCall['Multi'] = $Call['Call'];
                $Result[$Index] = Code::Run($OneCall, $Call['Mode']);
            }
            else
                $Result = null;

        return $Result;
    });