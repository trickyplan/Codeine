<?php

    /* Codeine
     * @author BreathLess
     * @description: Body Mass Index
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 3:42
     */

    self::Fn('Calculate', function ($Call)
    {
        return round($Call['Mass']/pow($Call['Height'],2), $Call['Precision']);
    });
