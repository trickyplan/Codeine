<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('WriteKeys', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Keys = array();

            foreach ($Call['Data'] as $Key => $Value)
                $Keys[] = '`'.$Key.'`';

            $Keys = '('.implode (',', $Keys).')';
        }

        return $Keys;
    });

    setFn('ReadKeys', function ($Call)
    {
        if(isset($Call['Keys']))
        {
            foreach ($Call['Keys'] as $Key)
                $Keys[] = '`'.$Call['Link']->quote ($Key).'`';

            $Keys = implode (',', $Keys);
        }
        else
            $Keys = '*';

        return $Keys;
    });

    setFn('Set', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Sets = array ();

            foreach ($Call['Data'] as $Key => $Value)
            {
                if (is_scalar($Value))
                {
                    if (is_float($Value) or !is_int($Value))
                        $Value = strtr($Value, ',','.');
                    // FIXME I'm shitcode

                    $Sets[] = '`'.$Key.'` = '. $Call['Link']->quote($Value);
                }
            }

            $Sets = implode(',', $Sets);
        }
        else
            $Sets = '';

        return $Sets;
    });

    setFn('Values', function ($Call)
    {
        $Values = '';

        if (isset($Call['Data']))
        {
            foreach ($Call['Data'] as &$Value)
                if (!is_float($Value) and !is_int($Value))
                    $Value = '\''.$Call['Link']->quote($Value). '\''; // ?
                else
                    $Value = strtr($Value, ',','.'); // FIXME I'm shitcode

            $Values = implode(',', $Call['Data']);
        }

        return ' values ('.$Values.')';
    });

    setFn('Table', function ($Call)
    {
        return '`' . strtr($Call['Scope'],array('/' => '', '.' => '')) . '`';
    });

    setFn('Sort', function ($Call)
    {
        $SortString = '';

        if (isset($Call['Sort']))
        {
            $Conditions = array ();


            foreach ($Call['Sort'] as $Key => $Direction)
                if (isset($Call['Nodes'][$Key]))
                    $Conditions[] = $Call['Link']->quote($Key)
                        .(is_numeric($Call['Nodes'][$Key])? '+0': '')
                        .' '.($Direction == 'ASC'? 'ASC': 'DESC');

            if (sizeof($Conditions)>0)
                $SortString = ' order by ' . implode(',', $Conditions);
        }


        return $SortString;
    });

    setFn('Limit', function ($Call)
    {
        if (isset($Call['Limit']))
            $LimitString = ' limit '.$Call['Limit']['From'].', '.$Call['Limit']['To']; // FIXME Checks
        else
            $LimitString = '';

        return $LimitString;
    });

    setFn ('Where', function ($Call)
    {
        if (isset($Call['Where']))
        {
            $WhereString = ' where ';

            $Conditions = array();

            foreach($Call['Where'] as $Key => $Value)
            {
                $Relation = '=';

                if (is_array($Value))
                {
                    foreach ($Value as $Relation => &$Value) // FIXME!
                    {
                        if (is_array($Value))
                            $Value = '('.implode(',', $Value).')';

                        if ($Relation == '$in')
                            $Relation = 'IN';

                        $Conditions[] = '`'.$Key.'` '. $Relation.' '.$Call['Link']->quote($Value);
                    }
                }
                else
                {
                    $Value = $Call['Link']->quote($Value);
                    $Conditions[] = '`'.$Key.'` '. $Relation.' '.$Value;
                }
            }

            $WhereString = $WhereString . ' ' . implode(' AND ', $Conditions);
        }
        else
            $WhereString = '';

        return $WhereString;
    });

    setFn('Read', function (array $Call)
    {
        return 'select '
               .F::Run(null, 'ReadKeys', $Call).
               ' from '.F::Run(null, 'Table', $Call)
               .F::Run(null, 'Where', $Call)
               .F::Run(null, 'Sort', $Call)
               .F::Run(null, 'Limit', $Call);
    });

    setFn('Insert', function (array $Call)
    {
        return 'insert into '
            .F::Run(null, 'Table', $Call)
            .F::Run(null, 'WriteKeys', $Call)
            .F::Run(null, 'Values', $Call);
    });

    setFn('Update', function ($Call)
    {
        return 'update '
            .F::Run(null, 'Table', $Call).
            ' set '.F::Run(null, 'Set', $Call)
            .F::Run(null, 'Where', $Call)
            .F::Run(null, 'Limit', $Call);
    });

    setFn('Delete', function ($Call)
    {
        return 'delete from '
            . F::Run(null, 'Table', $Call)
            . F::Run(null, 'Where', $Call)
            .F::Run(null, 'Limit', $Call);
    });

    setFn('Count', function (array $Call)
    {
        return 'select count(*) from '.F::Run(null, 'Table', $Call)
               .F::Run(null, 'Where', $Call);
    });