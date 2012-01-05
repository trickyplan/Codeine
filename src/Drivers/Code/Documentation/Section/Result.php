<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Do', function ($Call)
    {
        $Widgets = array();

        foreach ($Call['Result'] as $Key => $Value)
            $Widgets = array_merge ($Widgets, F::Run ('Code.Documentation.Section.' . $Key, 'Do', $Call, array($Key => $Value)));

        return $Widgets;
    });