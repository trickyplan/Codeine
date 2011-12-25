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

        foreach ($Call['Call'] as $Name => $Argument)
            $Widgets[] =
                array(
                    'Place'     => 'Content',
                    'Type'      => 'Heading',
                    'Level'     => 5,
                    'Value'     => $Name
                );

        return $Widgets;
    });