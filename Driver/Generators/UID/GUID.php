<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: GUID
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 14:56
     */

    self::Fn('Get', function ($Call)
    {
        // 22345200-abe8-4f60-90c8-0d43c5f6c0f6

        $Output = '';

        for ($ic = 0; $ic < 8; $ic++)
            $Output.=dechex(rand(0,15));
        $Output.= '-';

        for ($g = 0; $g<3; $g++)
        {
            for ($ic = 0; $ic < 4; $ic++)
                $Output.=dechex(rand(0,15));
            $Output.= '-';
        }

        for ($ic = 0; $ic < 12; $ic++)
            $Output.=dechex(rand(0,15));

        return $Output;
    });