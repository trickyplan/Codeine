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
                'Type' => 'Block',
                'Class' => 'DocumentDescriptionBlock',
                'Value' => $Call['Description'][$Call['Locale']]
            )
        );

        return $Widgets;
    });