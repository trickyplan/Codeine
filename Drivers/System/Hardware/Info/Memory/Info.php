<?php

    /* Codeine
     * @author BreathLess
     * @description: Free memory
     * @package Codeine
     * @version 6.0
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
