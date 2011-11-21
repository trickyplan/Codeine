<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Create', function ($Call)
    {
        return F::Run($Call, array(
                '_N' => 'Engine.Data'
            )
        );
    });

    self::Fn('Load', function ($Call)
    {
        $Data = F::Run($Call, array(
                        '_N' => 'Engine.Data'
                    )
                );
        return $Data;
    });

    self::Fn('Erase', function ($Call)
    {

    });

    self::Fn('Node.Add', function ($Call)
    {
        $Object = F::Run (array($Call,'_F' => 'Load'));

        if (isset($Object[$Call['Key']]))
        {
            if (is_array($Object[$Call['Key']]))
                $Object[$Call['Key']][] = $Call['Value'];
            else
                $Object[$Call['Key']] = array($Object[$Call['Key']], $Call['Value']);
        }
        else
            $Object[$Call['Key']] = $Call['Value'];

        F::Run($Call, array(
                    '_F' => 'Create',
                    'Value' => $Object
               ));
    });

    self::Fn('Node.Get', function ($Call)
    {

    });

    self::Fn('Node.Set', function ($Call)
    {
        $Object = F::Run ($Call, array('_F' => 'Load'));

        if (is_array($Object))
            $Object[$Call['Key']] = $Call['Value'];
        else
            $Object = array($Call['Key'] => $Call['Value']);

        return F::Run($Call, array(
                    '_F' => 'Create',
                    'Values' => $Object
               ));
    });

    self::Fn('Node.Del', function ($Call)
    {

    });