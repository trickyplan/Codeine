<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Keys', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Keys = array();

            foreach ($Call['Data'] as $Key => $Value)
                $Keys[] = $Call['Link']->real_escape_string ($Key); // ?

            $Keys = '('.implode (',', $Keys).')';
        }
        else
            $Keys = '*';

        return $Keys;
    });

    self::setFn('Set', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Sets = array ();

            foreach ($Call['Data'] as $Key => $Value)
                $Sets[] = $Call['Link']->real_escape_string($Key).' = \''. $Call['Link']->real_escape_string($Value).'\'';

            $Sets = implode(',', $Sets);
        }
        else
            $Sets = '';

        return $Sets;
    });

    self::setFn('Values', function ($Call)
    {

        $Values = '';
        if (isset($Call['Data']))
        {
            foreach ($Call['Data'] as &$Value)
                $Value = '\''.$Call['Link']->real_escape_string($Value). '\''; // ?

            $Values = implode(',', $Call['Data']);
        }

        return ' values ('.$Values.')';
    });

    self::setFn('Table', function ($Call)
    {
        return '`' . strtr($Call['Scope'],array('/' => '', '.' => '')) . '` ';
    });

    self::setFn ('Scope', function ($Call)
    {
        return ' from `'.$Call['Scope'].'`';
    });

    self::setFn('Sort', function ($Call)
    {
        if (isset($Call['Sort']))
        {
            $SortString = ' order by';

            $Conditions = array ();

            foreach ($Call['Sort'] as $Key => $Direction)
                $Conditions[] = $Call['Link']->real_escape_string($Key). ' '.($Direction == SORT_ASC? 'ASC': 'DESC');

            $SortString = $SortString . ' ' . implode(',', $Conditions);
        }
        else
            $SortString = '';

        return $SortString;
    });

    self::setFn ('Where', function ($Call)
    {
        if (isset($Call['Where']))
        {
            $WhereString = ' where ';

            $Conditions = array();

            foreach($Call['Where'] as $Key => $Value)
            {
                $Relation = '=';

                if (is_array($Value))
                    list($Relation, $Value) = $Value;

                $Conditions[] = '`'.$Key.'` '. $Relation.' \''.$Call['Link']->real_escape_string($Value).'\'';
            }

            $WhereString = $WhereString . ' ' . implode(' AND ', $Conditions);
        }
        else
            $WhereString = '';

        return $WhereString;
    });

    self::setFn('Read', function (array $Call)
    {
        return 'select '
               .F::Run(null, 'Keys', $Call).
               'from '.F::Run(null, 'Table', $Call)
               .F::Run(null, 'Where', $Call)
               .F::Run(null, 'Sort', $Call);
    });

    self::setFn('Insert', function (array $Call)
    {
        return 'insert into '
            .F::Run(null, 'Table', $Call)
            .F::Run(null, 'Keys', $Call)
            .F::Run(null, 'Values', $Call);
    });

    self::setFn('Update', function ($Call)
    {
        return 'update '
            .F::Run(null, 'Table', $Call).
            'set '.F::Run(null, 'Set', $Call)
            .F::Run(null, 'Where', $Call);
    });

    self::setFn('Delete', function ($Call)
    {
        return 'delete from '
            . F::Run(null, 'Table', $Call)
            . F::Run(null, 'Where', $Call);
    });