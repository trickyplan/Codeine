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

    self::Fn('Create', function ($Call)
    {
        $Fields = array();
        $Values = array();

        if (!isset($Call['Multiple']))
            $Call['Data'] = array($Call['Data']);

        foreach ($Call['Data'] as $IX => $Data)
            foreach ($Data as $Key => $Value) // :)))
            {
                $Fields[$Key] = '`'.mysql_real_escape_string($Key, $Call['Link']).'`';
                $Values[$IX][] = '\''.mysql_real_escape_string($Value, $Call['Link']).'\'';
            }

        foreach ($Values as $IX => $Value)
            $DValues[] = '('.implode(',', $Value).')';

        $QueryString = 'INSERT INTO '.$Call['Options']['Scope'].' ('
                       .implode(',',$Fields).') VALUES '.implode(',',$DValues);

        Code::On('Data.MySQL.Query', $QueryString);
        return $QueryString;
    });

    self::Fn('Read', function ($Call)
    {
        $Query = array();

        if (isset($Call['Where']))
        {
            foreach ($Call['Where'] as $Key => $Value)
                $Query[] = '`'.mysql_real_escape_string($Key, $Call['Link'])
                    .'` = "'.mysql_real_escape_string($Value, $Call['Link']).'"';
            $Where = ' WHERE '.implode (' AND ', $Query);
        }
        else
            $Where = '';

        $QueryString = 'SELECT * FROM '.$Call['Options']['Scope'].$Where;

        Code::On('Data.MySQL.Query', $QueryString);
        return $QueryString;
    });

    self::Fn('Update', function ($Call)
    {
        $QueryString = array();
        foreach ($Call['Set'] as $Set)
        {
            $Modification = array();
            $Where = array();

            foreach ($Set['Data'] as $Key => $Value)
                $Modification[] = '`'.mysql_real_escape_string($Key, $Call['Data']['Store']).'` = '
                         .'\''.mysql_real_escape_string($Value, $Call['Data']['Store']).'\'';

            foreach ($Set['Where'] as $Key => $Value)
                $Where[] = ' `'.mysql_real_escape_string($Key, $Call['Data']['Store'])
                          .'` = "'.mysql_real_escape_string($Value, $Call['Data']['Store']).'" ';

            $QueryString[] = 'UPDATE `'.$Call['Data']['Point']['Scope'].'` SET '.implode(',',$Modification).' WHERE '.implode('AND', $Where);
        }

        Code::On('Data.MySQL.Query', $QueryString);
        return $QueryString;
    });

    self::Fn('Delete', function ($Call)
    {
        $Query = array();

        foreach ($Call['Where'] as $Key => $Value)
            $Query[] = '`'.mysql_real_escape_string($Key, $Call['Link'])
                      .'` = "'.mysql_real_escape_string($Value, $Call['Link']).'"';

        $QueryString = 'DELETE FROM '.$Call['Options']['Scope'].' WHERE '
                       .implode (' AND ', $Query);

        Code::On('Data.MySQL.Query', $QueryString);
        return $QueryString;
    });

