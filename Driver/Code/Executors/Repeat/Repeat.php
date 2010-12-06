<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Repeat Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 21:54
     */

    self::Fn('Run', function ($Call)
    {
        $Output = array();
        
        for ($ic = 0; $ic < $Call['Count']; $ic++)
            $Output[$ic] = Code::Run($Call['Call']);

        return $Output;
    });