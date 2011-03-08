<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Standart Input Text
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 21:25
     */

    self::Fn('Make', function ($Call)
    {
        $ID    = isset($Call['ID'])   ? $Call['ID']: uniqid();
        $Name  = isset($Call['Name']) ? $Call['Name']: uniqid();
        $Value = isset($Call['Value']) ? $Call['Value']: '';
        $Label = isset($Call['Label']) ? $Call['Label']: $Name;

        $Layout = Data::Read('Layout::UI/Codeine/Textfield/Textfield');
        
        return str_replace(
            array('$ID', '$Name', '$Value', '$Label'),
            array($ID, $Name, $Value, $Label), $Layout);
    });
