<?php

    /* Codeine
     * @author BreathLess
     * @description: Standart SHA1
     * @package Codeine
     * @version 7.x
     * @date 22.11.10
     * @time 4:41
     */

    setFn('Get', function ($Call)
    {
        return hash('sha512', $Call['Value'].$Call['Salt']);
    });

