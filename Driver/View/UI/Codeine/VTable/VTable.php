<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: VTable Templater Codeine
     * @package Codeine
     * @subpackage Drivers
     * @version 
     * @date 18.11.10
     * @time 5:46
     */

    self::Fn('Make', function ($Call)
    {
        $Rows = array();

        $RowLayout = Data::Read('Layout::UI/Codeine/VTable/VTable_Row');

        $TableLayout = Data::Read('Layout::UI/Codeine/VTable/VTable');

        foreach ($Call['Item']['Data'][$Call['Item']['ID']] as $Key => $Value)
            $Rows[] = str_replace(array('<key/>', '<value/>'), array($Key, $Value),$RowLayout);
        
        return str_replace('<rows/>', implode("\n", $Rows), $TableLayout);
    });
