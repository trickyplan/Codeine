<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 15.03.11
     * @time 3:10
     */

    self::Fn('Do', function ($Call)
    {
        return Code::Run(array('N' => 'Data.Store.File.Flat', 'F' => 'Open'), Code::Ring2, null, 'Describe');
    });
