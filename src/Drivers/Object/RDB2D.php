<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Create', function ($Call)
    {
        $Call['Value'] = array($Call['Value']);
        
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

    });

    self::Fn('Node.Add', function ($Call)
    {

    });


    self::Fn('Node.Set', function ($Call)
    {

    });

    self::Fn('Node.Del', function ($Call)
    {

    });