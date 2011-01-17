<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Direct Router
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:16
     */

     self::Fn('Route', function ($Call)
     {
        $Routed = array();

        if (mb_strpos($Call['Call'], '?') !== false)
        {
            list($Call['Call'], $Query) = explode('?', $Call['Call']);
            parse_str($Query, $Routed);
        }

        $Routed['F'] = $Call['Call'];

      return $Routed;
    });