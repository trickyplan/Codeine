<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Do', function ($Call)
    {
        $Widgets = array();

        foreach ($Call['Call'] as $Name => $Argument)
        {
            $Widgets[] =
                array(
                    'Place'     => 'Content',
                    'Type'      => 'Heading',
                    'Level'     => 5,
                    'Value'     => $Name
                );

            foreach ($Argument as $Key => $Value)
                $Widgets = array_merge($Widgets, F::Run('Code.Documentation.Section.'.$Key, 'Do', $Call, array($Key => $Value)));
        }

        return $Widgets;
    });