<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Procedure-style MySQL Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
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
        $Rows = array();
        
        $Query = Code::Run(
            array(
                  'N' => 'Data.Syntax.SQL',
                  'F' => 'Read',
                  'D' => 'MySQL',
                  'Data' => $Call
                 ));

        if (!($Result = mysql_query($Query, $Call['Store'])))
            Code::On('Data', 'errDataMySQLReadFailed', $Call);

        while ($Row = mysql_fetch_assoc($Result))
            $Rows[] = $Row;
        
        return $Rows;
    });

    self::Fn('Create', function ($Call)
    {
        $Query = Code::Run(
            array(
                  'N' => 'Data.Syntax.SQL',
                  'F' => 'Create',
                  'D' => 'MySQL',
                  'Data' => $Call
                 ));
        
        if (!($Result = mysql_query($Query, $Call['Store'])))
        {
            Code::On('Data', 'errDataMySQLCreateFailed', $Call);
            return false;
        }

        return true;
    });

    self::Fn('Update', function ($Call)
    {
        $Query = Code::Run(
            array(
                  'N' => 'Data.Syntax.SQL',
                  'F' => 'Update',
                  'D' => 'MySQL',
                  'Data' => $Call
                 ));

        foreach ($Query as $cQuery)
        {
            if (!($Result = mysql_query($cQuery, $Call['Store'])))
            {
                Code::On('Data', 'errDataMySQLUpdateFailed', $Call);
                echo mysql_error();
                return false;
            }
        }

        return true;
    });