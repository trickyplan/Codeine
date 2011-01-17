<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Test Controller
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 13:38
     */

    self::Fn('Test', function ($Call)
    {
        $Structure = Structure::Make(
           array(
               'Nodes' =>
                   array(
                       'ROField' =>
                           array(
                               'NoRead' => true
                           )
                   )
           )
        );

        $Structure->ROField = 'fdgf';
        
        var_dump($Structure->ROField);
    });
