<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Escape', function ($Call)
    {
        if (!is_numeric($Call['Value']))
            return '"'.$Call['Value'].'"';
        else
            return $Call['Value'];
    });

    self::Fn('Find', function ($Call)
    {
        $WhereString = array();

        foreach ($Call['Where'] as $Key => $Value)
            if (is_array($Value))
                foreach ($Value as $Op => $Value)
                    $WhereString[] = '`'.$Key.'`'.$Op.' '.F::Run(
                                                            array(
                                                                '_N'=>'Data.Syntax.MySQL',
                                                                '_F' => 'Escape',
                                                                'Value' => $Value));
            else
                $WhereString[] = '`'.$Key.'`'.' = '.F::Run(
                                                            array(
                                                                '_N'=>'Data.Syntax.MySQL',
                                                                '_F' => 'Escape',
                                                                'Value' => $Value));

        return implode(' AND ', $WhereString);
    });

    self::Fn('Load', function ($Call)
    {
        if (isset($Call['ID']))
        {
            if (is_array($Call['ID']))
            {
                foreach ($Call['ID'] as &$ID)
                    $ID = F::Run(array('_N' => 'Data.Syntax.MySQL', '_F' => 'Escape', 'Value' => $ID));

                $Where = '`ID` IN ('.implode(',', $Call['ID']).')';
            }
            else
                $Where = '`ID` = '.F::Run(array('_N' => 'Data.Syntax.MySQL', '_F' => 'Escape', 'Value' => $Call['ID']));
        }
        else
            $Where = ' 1 = 1';

        return $Where;
    });

    self::Fn('Values', function ($Call)
        {
            $WhereString = array();

            if (isset($Call['Where']))
                {
                    foreach ($Call['Where'] as $Key => $Value)
                    if (is_array($Value))
                        foreach ($Value as $Op => $Value)
                            $WhereString[] = '`'.$Key.'`'.$Op.' '.F::Run(
                                                                    array(
                                                                        '_N'=>'Data.Syntax.MySQL',
                                                                        '_F' => 'Escape',
                                                                        'Value' => $Value));
                    else
                        $WhereString[] = '`'.$Key.'`'.' = '.F::Run(
                                                                    array(
                                                                        '_N'=>'Data.Syntax.MySQL',
                                                                        '_F' => 'Escape',
                                                                        'Value' => $Value));

                    return implode(' AND ', $WhereString);
                }
            else
                return '1 = 1';
        });

    self::Fn('Delete', function ($Call)
    {
        if (is_array($Call['ID']))
        {
            foreach ($Call['ID'] as &$ID)
                $ID = F::Run(array('_N' => 'Data.Syntax.MySQL', '_F' => 'Escape', 'Value' => $ID));

            $Where = '`ID` IN '.implode(',', $Call['ID']);
        }
        else
            $Where = '`ID` = '.F::Run(array('_N' => 'Data.Syntax.MySQL', '_F' => 'Escape', 'Value' => $Call['ID']));

        return $Where;
    });


    self::Fn('Create', function ($Call)
    {
        foreach ($Call['Value'] as $Row)
        {
            $Keys = array();
            
            foreach ($Row as $Key => &$Value)
            {
                $Row['ID'] = $Call['ID'];
                $Keys[] = '`'.$Key.'`';
                $Value = F::Run(array('_N' => 'Data.Syntax.MySQL', '_F' => 'Escape', 'Value' => $Value));
            }

            $Keys = implode(',', $Keys);

            $Data[$Keys][] = '('.implode(',',$Row).')';
        }

        return $Data;
    });

    self::Fn('Update', function ($Call)
    {
        $Sets = array();
        
        foreach ($Call['Set'] as $Key => &$Value)
            $Sets[] = '`'.$Key.'` = '.F::Run(array('_N' => 'Data.Syntax.MySQL', '_F' => 'Escape', 'Value' => $Value));

        $Data = implode(',',$Sets);
        return $Data;
    });