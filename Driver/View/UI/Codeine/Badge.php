<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Block Element
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 22.11.10
     * @time 6:06
     */

    self::Fn('Make', function ($Call)
    {
        $ID    = isset($Call['ID'])   ? $Call['ID']: uniqid();
        $Value = isset($Call['Value']) ? $Call['Value']: '';
        $Class = isset($Call['Class']) ? $Call['Class']: array();

        $Layout = Data::Read('Layout::UI/Codeine/Badge/Badge');

        return str_replace(
            array('$ID', '$Value', '$Class'),
            array($ID, $Value, implode(' ',$Class)), $Layout);
    });
