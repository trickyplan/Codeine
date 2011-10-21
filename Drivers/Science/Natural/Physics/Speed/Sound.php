<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 3:49
     */

    self::Fn('Get', function ($Call)
    {
        return $Call['Variables']['Speeds'][$Call['Environment']];
    });
