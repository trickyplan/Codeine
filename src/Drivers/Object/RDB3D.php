<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Create', function ($Call)
    {
        $Values = array();
        
        foreach ($Call['Value'] as $Key => $Value)
            $Values[] = array('K' => $Key, 'V' => $Value);

        $Call['Value'] = $Values;
        
        return F::Run($Call, array(
               '_N' => 'Engine.IO'
           )
        );
    });

    self::setFn('Load', function ($Call)
    {
        $Rows = F::Run($Call, array(
                               '_N' => 'Engine.IO'
                           ));

        $Data = array();

        if (null !== $Rows)
        {
            foreach ($Rows as $Row)
                $Data[$Row['ID']][$Row['K']][] = $Row['V'];

            foreach ($Data as &$Object)
                foreach ($Object as $Key => &$Value)
                    if (count($Value) == 1)
                        $Value = $Value[0];
        }
        else
            $Data = null;

        return $Data;
    });

    self::setFn('Find', function ($Call)
    {
        $Where = array();

        foreach ($Call['Where'] as $Key => $Value)
        {
            $Where['K'] = $Key;
            $Where['V'] = $Value;
        }

        $Call['Where'] = $Where;

        $Rows = F::Run($Call, array(
                               '_N' => 'Engine.IO'
                           ));

        if (count($Rows) == 1)
        return $Rows[0];
    });

    self::setFn('Erase', function ($Call)
    {

    });

    self::setFn('Node.Add', function ($Call)
    {
        $Call['Value'] = array(
            array('K' => $Call['Key'], 'V' => $Call['Value'])
        );

        return F::Run($Call, array(
                       '_N' => 'Engine.IO',
                       '_F' => 'Create'
                   )
                );
    });


    self::setFn('Node.Set', function ($Call)
    {
        $Call['Set'] =
            array('K' => $Call['Key'], 'V' => $Call['Value']
        );

        $Call['Where'] = array(
            'ID' => $Call['ID'],
            'K' => $Call['Key']
        );

        return F::Run($Call, array(
                       '_N' => 'Engine.IO',
                       '_F' => 'Update'
                   )
                );
    });

    self::setFn('Node.Del', function ($Call)
    {

    });