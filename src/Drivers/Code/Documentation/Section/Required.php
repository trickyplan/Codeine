<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Do', function ($Call)
    {
        $Widgets = array(
            array(
                'Place' => 'Content',
                'Type' => 'Block',
                'Class' => array('Documentation_Required_Block', 'Documentation_Required_Block_'.($Call['Required']? 'True': 'False')),
                'Value' => $Call['Required']? '<l>Code.Documentation.Call.Required.True</l>': '<l>Code.Documentation.Call.Required.False</l>'
            )
        );

        return $Widgets;
    });