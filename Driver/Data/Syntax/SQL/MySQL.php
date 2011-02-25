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
        $Query = array();

        foreach ($Call['Where'] as $Key => $Value)
            $Query[] = '`'.mysql_real_escape_string($Key, $Call['Link'])
                      .'` = "'.mysql_real_escape_string($Value, $Call['Link']).'"';

        $QueryString = 'SELECT * FROM '.$Call['Options']['Scope'].' WHERE '
                       .implode (' AND ', $Query);

        return $QueryString;
    });

    self::Fn('Create', function ($Call)
    {
        $Fields = array();
        $Values = array();

        if (!isset($Call['Data']['Multiple']))
            $Call['Data']['Data']['Data'] = array($Call['Data']['Data']['Data']);

        foreach ($Call['Data']['Data']['Data'] as $IX => $Data)
            foreach ($Data as $Key => $Value) // :)))
            {
                $Fields[$Key] = '`'.mysql_real_escape_string($Key, $Call['Data']['Store']).'`';
                $Values[$IX][] = '\''.mysql_real_escape_string($Value, $Call['Data']['Store']).'\'';
            }

        foreach ($Values as $IX => $Value)
            $DValues[] = '('.implode(',', $Value).')';

        $QueryString = 'INSERT INTO '.$Call['Data']['Point']['Scope'].' ('
                       .implode(',',$Fields).') VALUES '.implode(',',$DValues);

        return $QueryString;
    });

    self::Fn('Update', function ($Call)
    {
        $QueryString = array();
        foreach ($Call['Data']['Data']['Set'] as $Set)
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

        return $QueryString;
    });
