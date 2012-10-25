<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('WriteKeys', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Keys = array();

            foreach ($Call['Data'] as $Key => $Value)
                $Keys[] = '`'.$Call['Link']->real_escape_string ($Key).'`';

            $Keys = '('.implode (',', $Keys).')';
        }

        return $Keys;
    });

    self::setFn('ReadKeys', function ($Call)
    {
        if(isset($Call['Keys']))
        {
            foreach ($Call['Keys'] as $Key)
                $Keys[] = '`'.$Call['Link']->real_escape_string ($Key).'`';

            $Keys = implode (',', $Keys);
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
                $Sets[] = '`'.$Call['Link']->real_escape_string($Key).'` = \''. $Call['Link']->real_escape_string($Value).'\'';

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
        return '`' . strtr($Call['Scope'],array('/' => '', '.' => '')) . '`';
    });

    self::setFn('Sort', function ($Call)
    {
        $SortString = '';

        if (isset($Call['Sort']))
        {
            $Conditions = array ();


            foreach ($Call['Sort'] as $Key => $Direction)
                if (isset($Call['Nodes'][$Key]))
                    $Conditions[] = $Call['Link']->real_escape_string($Key)
                        .(is_numeric($Call['Nodes'][$Key])? '+0': '')
                        .' '.($Direction == SORT_ASC? 'ASC': 'DESC');

            if (sizeof($Conditions)>0)
                $SortString = ' order by ' . implode(',', $Conditions);
        }


        return $SortString;
    });

    self::setFn('Limit', function ($Call)
    {
        if (isset($Call['Limit']))
            $LimitString = ' limit '.$Call['Limit']['From'].', '.$Call['Limit']['To']; // FIXME Checks
        else
            $LimitString = '';

        return $LimitString;
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
                {
                    foreach ($Value as $Relation => &$Value)
                    {
                        if (is_array($Value))
                        {
                            $Value = '('.implode(',', $Value).')';
                            $Quote = false;
                        }
                        else
                            $Quote = !is_numeric($Value);


                    }
                }
                else
                {
                    $Value = $Call['Link']->real_escape_string($Value);
                    $Quote = true;
                }

                $Conditions[] = '`'.$Key.'` '. $Relation.' '.($Quote ? '\''.$Value.'\'': $Value);
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
               .F::Run(null, 'ReadKeys', $Call).
               ' from '.F::Run(null, 'Table', $Call)
               .F::Run(null, 'Where', $Call)
               .F::Run(null, 'Sort', $Call)
               .F::Run(null, 'Limit', $Call);
    });

    self::setFn('Insert', function (array $Call)
    {
        return 'insert into '
            .F::Run(null, 'Table', $Call)
            .F::Run(null, 'WriteKeys', $Call)
            .F::Run(null, 'Values', $Call);
    });

    self::setFn('Update', function ($Call)
    {
        return 'update '
            .F::Run(null, 'Table', $Call).
            'set '.F::Run(null, 'Set', $Call)
            .F::Run(null, 'Where', $Call)
            .F::Run(null, 'Limit', $Call);
    });

    self::setFn('Delete', function ($Call)
    {
        return 'delete from '
            . F::Run(null, 'Table', $Call)
            . F::Run(null, 'Where', $Call)
            .F::Run(null, 'Limit', $Call);
    });

    self::setFn('Count', function (array $Call)
    {
        return 'select count(*) from '.F::Run(null, 'Table', $Call)
               .F::Run(null, 'Where', $Call);
    });