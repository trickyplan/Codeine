<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Ex-2D Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 16.11.10
     * @time 4:40
     */

    self::Fn('afterRead', function ($Call)
    {
        $Data = array();

        foreach ($Call['Result'] as $Row)
            $Data[$Row['ID']] = $Row;

        return $Data;
    });
