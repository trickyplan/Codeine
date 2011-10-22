<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Create', function ($Call)
    {
        $Values = array();
        
        foreach ($Call['Value'] as $Key => $Value)
            $Values[] = array('K' => $Key, 'V' => $Value);

        $Call['Value'] = $Values;
        
        return F::Run($Call, array(
               '_N' => 'Engine.Data'
           )
        );
    });

    self::Fn('Load', function ($Call)
    {
        $Rows = F::Run($Call, array(
                               '_N' => 'Engine.Data'
                           ));

        $Data = array();

        if (null !== $Rows)
        {
            foreach ($Rows as $Row)
                $Data[$Row['K']][] = $Row['V'];

            foreach ($Data as &$Value)
                        if (count($Value) == 1)
                            $Value = $Value[0];
        }
        else
            $Data = null;
        
        return $Data;
    });

    self::Fn('Find', function ($Call)
    {
        $Where = array();

        foreach ($Call['Where'] as $Key => $Value)
        {
            $Where['K'] = $Key;
            $Where['V'] = $Value;
        }

        $Call['Where'] = $Where;

        $Rows = F::Run($Call, array(
                               '_N' => 'Engine.Data'
                           ));

        if (count($Rows) == 1)
        return $Rows[0];
    });

    self::Fn('Erase', function ($Call)
    {

    });

    self::Fn('Node.Add', function ($Call)
    {
        $Call['Value'] = array(
            array('K' => $Call['Key'], 'V' => $Call['Value'])
        );

        return F::Run($Call, array(
                       '_N' => 'Engine.Data',
                       '_F' => 'Create'
                   )
                );
    });


    self::Fn('Node.Set', function ($Call)
    {
        $Call['Set'] =
            array('K' => $Call['Key'], 'V' => $Call['Value']
        );

        $Call['Where'] = array(
            'ID' => $Call['ID'],
            'K' => $Call['Key']
        );

        return F::Run($Call, array(
                       '_N' => 'Engine.Data',
                       '_F' => 'Update'
                   )
                );
    });

    self::Fn('Node.Del', function ($Call)
    {

    });