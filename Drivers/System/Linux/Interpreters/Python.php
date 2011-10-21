<?php

    /* Codeine
     * @author BreathLess
     * @description: Python Wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:27
     */

    self::Fn('Exec', function ($Call)
    {
        return passthru('python -c \''.$Call['Value'].'\'');
    });
