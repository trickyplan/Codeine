<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Last Updated strategy
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 24.11.10
     * @time 2:43
     */

    self::Fn('Select', function ($Call)
    {
        $Times = array();

        foreach ($Call['Drivers'] as $Driver)
            $Times[$Driver] = filemtime(Data::Locate('Driver', $Call['Namespace'].'/'.$Driver.'.php'));

        arsort($Times);
        list ($Last) = $Times;
        
        return $Last;
    });
