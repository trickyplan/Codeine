<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Syntax
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 22:12
     */

    self::Fn('Read', function ($Call)
    {
        $QueryString = 'SELECT * FROM '.$Call['Data']['Point']['Scope'].' WHERE ';

        foreach ($Call['Data']['Data']['Where'] as $Key => $Value)
            $Query[] = '`'.mysql_real_escape_string($Key, $Call['Data']['Store'])
                      .'` = "'.mysql_real_escape_string($Value, $Call['Data']['Store']).'"';

        $QueryString.= implode (' AND ', $Query);

        return $QueryString;
    });