<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Do', function ($Call)
    {
        $Widgets = array(
            array(
                'Place' => 'Content',
                'Type' => 'Block',
                'Class' => 'Documentation_Type_Block',
                'Value' => '<l>Code.Documentation.Size</l>: '.$Call['Size'].' <l>Units.Information.Byte</l>'
            )
        );

        return $Widgets;
    });