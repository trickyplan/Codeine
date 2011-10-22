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
                    'Scope' => 'Page',
                    'ID' => 'About',
                    'Value' => array('Title' => 'NewPage','Body' => 'Body', 'Stats.Views' => 0)
                )
            );
    });

    self::Fn('Load', function ($Call)
    {
        return F::Run(
                       array(
                           '_N' => 'Engine.Object',
                           '_F' => 'Load',
                           'Scope' => 'Page',
                           'ID' => 'About'
                       )
                   );
    });

    self::Fn('Erase', function ($Call)
    {

    });

    self::Fn('Node.Add', function ($Call)
    {

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