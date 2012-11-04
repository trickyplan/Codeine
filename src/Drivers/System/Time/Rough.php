<?php

    /* Codeine
     * @author BreathLess
     * @description: time() wrapper
     * @package Codeine
     * @version 7.x
     * @date 09.03.11
     * @time 16:34
     */

    setFn('Get', function ($Call)
    {
        return floor(time()/$Call['Precision'])*$Call['Precision'];
    });
