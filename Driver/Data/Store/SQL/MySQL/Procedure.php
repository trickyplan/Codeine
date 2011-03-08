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

    self::Fn('Open', function ($Call)
    {
        $Link = mysql_connect(
            $Call['Options']['Server'].':'.$Call['Options']['Port'],
            $Call['Options']['Username'],
            $Call['Options']['Password']);

        if (!$Link)
        {
            Code::On('Data.MySQL.Open.Failed', $Call);
            $Link = null;
        }

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
            Code::On('Data.MySQL.Read.Failed', $Call);

        while ($Row = mysql_fetch_assoc($Result))
            $Rows[] = $Row;
        
        return $Rows;
    });

    self::Fn('Create', function ($Call)
    {
        $Query = Code::Run(
            Code::Current(array(
                  'N' => 'Data.Syntax.SQL',
                  'F' => 'Create',
                  'D' => 'MySQL'
            )));
        
        if (!($Result = mysql_query($Query, $Call['Link'])))
            Code::On('Data.MySQL.Create.Failed', $Call);

        return true;
    });

    self::Fn('Update', function ($Call)
    {
        $Query = Code::Run(
            Code::Current(array(
                  'N' => 'Data.Syntax.SQL',
                  'F' => 'Update',
                  'D' => 'MySQL'
            )));

        foreach ($Query as $cQuery)
            if (!($Result = mysql_query($cQuery, $Call['Store'])))
                Code::On('Data.MySQL.Update.Failed', $Call);

        return true;
    });

    self::Fn('Delete', function ($Call)
    {
        $Query = Code::Run(
            Code::Current(array(
                  'N' => 'Data.Syntax.SQL',
                  'F' => 'Delete',
                  'D' => 'MySQL'
            )));

            if (!($Result = mysql_query($Query, $Call['Link'])))
                Code::On('Data.MySQL.Delete.Failed', $Call);

        return true;
    });
