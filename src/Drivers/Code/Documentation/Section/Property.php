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

        foreach ($Call['Property'] as $Key => $Value)
            if ($Value)
                $Widgets[] =
                    array(
                        'Place' => 'Content',
                        'Type' => 'Block',
                        'Class' => 'DocumentPropertyBlock',
                        'Value' => '<l>Code.Documentation.Property.'.$Key.'</l>'
                    );

        return $Widgets;
    });