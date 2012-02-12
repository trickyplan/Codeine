<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
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

    self::setFn('Values', function ($Call)
    {
        if (isset($Call['Data']))
        {
            foreach ($Call['Data'] as &$Value)
                $Value = '\''.$Call['Link']->real_escape_string($Value). '\''; // ?

            $Values = implode(',', $Call['Data']);
        }

        return ' values ('.$Values.')';
    });

    self::setFn('Into', function ($Call)
    {
        return ' into `' . $Call['Scope'] . '` ';
    });

    self::setFn ('Scope', function ($Call)
    {
        return ' from `'.$Call['Scope'].'`';
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
               .F::Run('IO.Syntax.MySQL', 'Keys', $Call)
               .F::Run('IO.Syntax.MySQL', 'Scope', $Call)
               .F::Run('IO.Syntax.MySQL', 'Where', $Call);
    });

    self::setFn('Insert', function (array $Call)
    {
        return 'insert '
            .F::Run('IO.Syntax.MySQL', 'Into', $Call)
            .F::Run('IO.Syntax.MySQL', 'Keys', $Call)
            .F::Run('IO.Syntax.MySQL', 'Values', $Call);
    });