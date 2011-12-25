<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Do', function ($Call)
    {
        $Widgets = array(
            array(
                'Place' => 'Content',
                'Type'  => 'Heading',
                'Localized' => true,
                'Level' => 3,
                'Value' => 'Code.Documentation.Section.Methods'
            )
        );

        return $Widgets;
    });