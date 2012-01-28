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
                'Class' => 'Documentation_Type_Block',
                'Value' => '<l>Code.Documentation.Type</l>: <l>Code.Documentation.Type.'.$Call['Type'].'</l>'
            )
        );

        return $Widgets;
    });