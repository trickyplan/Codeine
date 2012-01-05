<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Keys', function ($Call)
    {
        if (isset($Call['Keys']))
        {
            foreach ($Call['Keys'] as &$Key)
                $Key = $Call['Link']->real_escape_string ($Key); // ?

            $Keys = implode (',', $Call['Keys']);
        }
        else
            $Keys = '*';

        return $Keys;
    });

    self::setFn ('Scope', function ($Call)
    {
        return ' from `'.$Call['Scope'].'`';
    });

    self::setFn ('Where', function ($Call)
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

        return $WhereString.' '.implode(' AND ', $Conditions);
    });

    self::setFn('Read', function (array $Call)
    {
        return 'select '
               .F::Run('IO.Syntax.MySQL', 'Keys', $Call)
               .F::Run('IO.Syntax.MySQL', 'Scope', $Call)
               .F::Run('IO.Syntax.MySQL', 'Where', $Call);
    });