<?php

    /* Codeine
     * @author BreathLess
     * @description: Uniqid Prefix
     * @package Codeine
     * @version 6.0
     * @date 04.12.10
     * @time 14:53
     */

    self::Fn('Get', function ($Call)
    {
        return uniqid($Call['Prefix'],true);
    });
