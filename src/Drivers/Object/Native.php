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
        return F::Run($Call, array(
                        '_N' => 'Engine.Data'
                    )
                );
    });

    self::Fn('Erase', function ($Call)
    {
        return F::Run($Call, array(
                        '_N' => 'Engine.Data'
                    )
                );
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
        return F::Run ($Call, array('_N' => 'Engine.Data', '_F' => 'Update'));
    });

    self::Fn('Node.Del', function ($Call)
    {

    });