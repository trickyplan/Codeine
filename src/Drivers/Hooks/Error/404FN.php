<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Show', function ($Call)
        {
            $Call['Value'] = array(
                'Place'   => 'Content',
                'Type'    => 'Heading',
                'Level'   => 3,
                'Value'   => '404',
                'Subtext' => '<l>' . $Call['_N'] . '.Subtext</l>'
            );

            return $Call;
        });