<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Create', function ($Call)
    {
        return F::Run($Call, array(
                '_N' => 'Engine.Data'
            )
        );
    });

    self::setFn('Load', function ($Call)
    {
        return F::Run($Call, array(
                        '_N' => 'Engine.Data'
                    )
                );
    });

    self::setFn('Erase', function ($Call)
    {
        return F::Run($Call, array(
                        '_N' => 'Engine.Data'
                    )
                );
    });

    self::setFn('Node.Add', function ($Call)
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

    self::setFn('Node.Get', function ($Call)
    {

    });

    self::setFn('Node.Set', function ($Call)
    {
        return F::Run ($Call, array('_N' => 'Engine.Data', '_F' => 'Update'));
    });

    self::setFn('Node.Del', function ($Call)
    {

    });