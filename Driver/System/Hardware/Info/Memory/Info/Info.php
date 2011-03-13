<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Free memory
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 0:33
     */

    self::Fn('Get', function ($Call)
    {
        exec('cat /proc/meminfo', $exec);
        foreach ($exec as $row)
        {
            list($key, $value) = explode(':', $row);
            $data[trim($key)] = trim($value);
        }
        return $data;
    });
