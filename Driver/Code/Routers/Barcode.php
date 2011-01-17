<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Barcode Router
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 15.11.10
     * @time 10:55
     */

    self::Fn('Route', function ($Call)
    {
        return  Code::Run(
            array('N'=>'Recognition.Barcode', 'F'=>'Recognize', 'Value' => $Call['Call'])
        );
    });