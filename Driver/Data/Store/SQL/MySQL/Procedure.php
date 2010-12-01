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

    self::Fn('Connect', function ($Call)
    {
        $Link = mysql_connect(
            $Call['Point']['Server'].':'.$Call['Point']['Port'],
            $Call['Point']['Username'],
            $Call['Point']['Password']);

        if (!$Link)
            Code::On('Data', 'errDataMySQLConnectFailed', $Call);

        if (!mysql_select_db($Call['Point']['Database'], $Link))
            Code::On('Data', 'errDataMySQLSelectDBFailed', $Call);

        if (!mysql_set_charset($Call['Point']['Charset'], $Link))
            Code::On('Data', 'errDataMySQLCharsetFailed', $Call);

        return $Link;
    });

    self::Fn('Read', function ($Call)
    {
        $Query = Code::Run(
            array(
                  'F' => 'Data/Syntax/SQL::Read',
                  'D' => 'MySQL',
                  'Data' => $Call
                 ));

        if (!($Result = mysql_query($Query, $Call['Store'])))
            Code::On('Data', 'errDataMySQLReadFailed', $Call);

        return mysql_fetch_assoc($Result);
    });