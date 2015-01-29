<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: microtime() wrapper
     * @package Codeine
     * @version 8.x
     * @date 09.03.11
     * @time 16:34
     */

    setFn('Get', function ($Call)
    {
        return system('date +%s%N');
    });
