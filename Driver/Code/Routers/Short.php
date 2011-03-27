<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 13.03.11
     * @time 21:50
     */

    self::Fn('Route', function ($Call)
    {
        if (is_string($Call['Call']) &&
            preg_match('@([\S]+)::([\S]+)\((.*)\)@SsUu', $Call['Call'], $Matches))
        {
            $Routed = array();
            list($All, $Routed['N'], $Routed['F'], $Args) = $Matches;

            if (!empty($Args))
            {
                $Args = json_decode($Args, true);

                $Routed = array_merge($Routed, $Args);
            }

            return $Routed;
        }
        else
            return null;
    });
