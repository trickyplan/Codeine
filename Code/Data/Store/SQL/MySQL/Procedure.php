<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Procedure-style MySQL Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 15.11.10
     * @time 22:26
     */

    $Connect = function ($Call)
    {
        $Link = mysql_connect($Call['Point']['Server'].':'.$Call['Point']['Port'], $Call['Point']['Username'], $Call['Point']['Password']);

        mysql_select_db($Call['Point']['Database'], $Link);

        mysql_set_charset($Call['Point']['Charset'], $Link);

        return $Link;
    };

    $Read = function ($Call)
    {
        $QueryString = 'SELECT * FROM '.$Call['Point']['Scope'].' WHERE ';

        foreach ($Call['Data'] as $Key => $Value)
            $Query[] = '`'.mysql_real_escape_string($Key, $Call['Store'])
                      .'` = "'.mysql_real_escape_string($Value, $Call['Store']).'"';

        $QueryString.= implode (' AND ', $Query);

        // var_dump($QueryString);

        if (!($Result = mysql_query($QueryString, $Call['Store'])))
            throw new WTF($QueryString.'<br/>'.mysql_error($Call['Store']));


        return mysql_fetch_assoc($Result);
    };