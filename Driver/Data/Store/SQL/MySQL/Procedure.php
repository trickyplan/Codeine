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
            $Call['Options']['Server'].':'.$Call['Options']['Port'],
            $Call['Options']['Username'],
            $Call['Options']['Password']);

        if (!$Link)
            Code::On('Data.MySQL.Connect.Failed', $Call);

        if (!mysql_select_db($Call['Options']['Database'], $Link))
            Code::On('Data.MySQL.SelectDB.Failed', $Call);

        if (!mysql_set_charset($Call['Options']['Charset'], $Link))
            Code::On('Data.MySQL.Charset.Failed', $Call);

        return $Link;
    });

    self::Fn('Read', function ($Call)
    {
        $Rows = array();

        $Query = Code::Run(
                    Code::Current(
                        array(
                          'N' => 'Data.Syntax.SQL',
                          'F' => 'Read',
                          'D' => 'MySQL'
                         )));

        if (!($Result = mysql_query($Query, $Call['Link'])))
            Code::On('errDataMySQLReadFailed', $Call);

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
            Code::On('Data.MySQL.Create.Failed', $Call);
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
                Code::On('errDataMySQLUpdateFailed', $Call);
                echo mysql_error();
                return false;
            }
        }

        return true;
    });
