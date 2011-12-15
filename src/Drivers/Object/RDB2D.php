<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Create', function ($Call)
    {
        $Call['Value'] = array($Call['Value']);
        
        return F::Run($Call, array(
                '_N' => 'Engine.IO'
            )
        );
    });

    self::setFn('Load', function ($Call)
    {
        return F::Run($Call, array(
                        '_N' => 'Engine.IO'
                    )
                );
    });

    self::setFn('Erase', function ($Call)
    {

    });

    self::setFn('Node.Add', function ($Call)
    {

    });


    self::setFn('Node.Set', function ($Call)
    {

    });

    self::setFn('Node.Del', function ($Call)
    {

    });