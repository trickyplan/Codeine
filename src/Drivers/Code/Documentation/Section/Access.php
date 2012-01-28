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
                'Type'  => 'Block',
                'Class' => 'Documentation_Access_Block',
                'Value' => '<l>Code.Documentation.Access.Free</l>'
            ));

        return $Widgets;
    });