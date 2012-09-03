<?php

    /* Codeine
     * @author BreathLess
     * @description: Random integer
     * @package Codeine
     * @version 7.x
     * @date 04.12.10
     * @time 14:56
     */

    self::setFn('Get', function ($Call)
    {
        $Output = '';

        for($IC = 0; $IC<$Call['Size']; $IC++)
            $Output.= rand(0,9);

        return $Output;
    });
