<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        $Uptime = explode(' ',trim(shell_exec('cat /proc/uptime')));
        return array_pop($Uptime);
    });