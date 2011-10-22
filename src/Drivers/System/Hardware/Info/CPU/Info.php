<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 11.03.11
     * @time 0:08
     */

    self::Fn('Get', function ($Call)
    {
        exec('cat /proc/cpuinfo', $exec);
        $CPU = 0;
        foreach ($exec as $row)
        {
            if (strpos($row, ':'))
            {
                list($key, $value) = explode(':', $row);
                $data[$CPU][trim($key)] = trim($value);
            }
            else
                $CPU++;
        }

        foreach ($data as &$CPU)
            $CPU['flags'] = explode(' ', $CPU['flags']);

        return $data;
    });
