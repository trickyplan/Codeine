<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Submit Button
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 21:55
     */

    self::Fn('Make', function ($Call)
    {
        $Layout = Data::Read( array('Point' => 'Layout',
                    'Where' => array('ID'=>'UI/Codeine/Submit/Submit')));

        $ID    = isset($Call['ID'])   ? $Call['ID']: uniqid();
        $Name  = isset($Call['Name']) ? $Call['Name']: uniqid();
        $Value = isset($Call['Value']) ? $Call['Value']: 'Submit';

        return str_replace(
            array('$ID', '$Name', '$Value', ),
            array($ID, $Name, $Value), $Layout);
    });