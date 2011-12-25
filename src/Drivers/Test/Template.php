<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Root', function ($Call)
    {
        $Call['Renderer'] = 'View.Render.HTML';

        $Call['Value'] =
            array(
                array(
                    'Place' => 'Content',
                    'Type' => 'Heading',
                    'Level' => 1,
                    'Value' => 'Codeine 7 works!'
                )
            );

        return $Call;
    });