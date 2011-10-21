<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Create', function ($Call)
    {
        return F::Run(
                array(
                    '_N' => 'Engine.Object',
                    '_F' => 'Create',
                    'Scope' => 'User',
                    'ID' => 'Test',
                    'Value' => array('Name' => 'Alice','Surname' => 'Liddel', 'Stats.Views' => 0)
                )
            );
    });

    self::Fn('Load', function ($Call)
    {
        return F::Run(
                       array(
                           '_N' => 'Engine.Object',
                           '_F' => 'Load',
                           'Scope' => 'User',
                           'ID' => 'Test'
                       )
                   );
    });

    self::Fn('Erase', function ($Call)
    {

    });

    self::Fn('Node.Add', function ($Call)
    {
        return F::Run(
                       array(
                           '_N' => 'Engine.Object',
                           '_F' => 'Node.Add',
                           'Scope' => 'User',
                           'ID' => 'Test',
                           'Key' => 'Keyed',
                           'Value' => 'Value'
                       )
                   );
    });

    self::Fn('Node.Get', function ($Call)
    {

    });

    self::Fn('Node.Set', function ($Call)
    {

    });

    self::Fn('Node.Del', function ($Call)
    {

    });