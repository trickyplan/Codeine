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
                $Keys[] = '`'.$Call['Link']->real_escape_string ($Key).'`';

            $Keys = '('.implode (',', $Keys).')';
        }

        return $Keys;
    });

    setFn('ReadKeys', function ($Call)
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

    setFn('Set', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Sets = array ();

            foreach ($Call['Data'] as $Key => $Value)
            {
                if (is_scalar($Value))
                {
                    if (!is_float($Value) and !is_int($Value))
                        $Value = '\''.$Call['Link']->real_escape_string($Value).'\''; // ?
                    else
                        $Value = strtr($Value, ',','.');
                    // FIXME I'm shitcode

                    $Sets[] = '`'.$Call['Link']->real_escape_string($Key).'` = '.$Value;
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
                    $Value = '\''.$Call['Link']->real_escape_string($Value). '\''; // ?
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
            $Conditions = [];

            foreach ($Call['Sort'] as $Key => $Direction)
                if (isset($Call['Nodes'][$Key]))
                    $Conditions[] = $Call['Link']->real_escape_string($Key)
                        .(is_numeric($Call['Nodes'][$Key])? '+0': '')
                        .' '.($Direction? 'ASC': 'DESC');

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

    setFn('Join', function ($Call)
    {


        return $Call;
    });

    setFn ('Where', function ($Call)
    {
        if (isset($Call['Where']))
        {
            $WhereString = ' where ';

            $Conditions = [];

            if (is_array($Call['Where']))
                foreach($Call['Where'] as $Key => $Value)
                {
                    $Relation = '=';

                    if (is_array($Value) && !empty($Value))
                    {
                        foreach ($Value as $Relation => &$Value) // FIXME!
                        {
                            if (!empty($Value))
                            {
                                if (is_array($Value))
                                {
                                    if (!empty($Value[0]))
                                    {
                                        $Value = '('.implode(',', $Value).')';
                                        $Quote = false;
                                    }
                                    else
                                    {
                                        unset($Value);
                                        $Quote = true;
                                    }
                                }
                                else
                                    $Quote = !is_numeric($Value);

                                switch ($Relation)
                                {
                                    case '$in': $Relation = 'IN'; break;
                                    case '$ne': $Relation = '<>'; break;
                                    case 'Like': $Value = '%'.$Value.'%';
                                    break;
                                }

                                $Conditions[] = '`'.$Key.'` '. $Relation.' '.($Quote ? '\''.$Value.'\'': $Value);
                            }
                        }
                    }
                    else
                    {
                        $Quote = !is_numeric($Value);
                        $Conditions[] = '`'.$Key.'` '. $Relation.' '.($Quote ? '\''.$Value.'\'': $Value);
                    }
                }

            if (!empty($Conditions) && $Call['Where'] !== null)
                $WhereString = $WhereString . ' ' . implode(' AND ', $Conditions);
            else
                $WhereString = $WhereString .'1=0';
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