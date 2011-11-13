<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Show', function ($Call)
        {
            header ("HTTP/1.0 404 Not Found");
d(__FILE__, __LINE__, $Call);
            $Call['Widgets'] = array(
                'Place'   => 'Content',
                'Type'    => 'Heading',
                'Level'   => 3,
                'Value'   => '404',
                'Subtext' => '<l>' . $Call['_N'] . '.Subtext</l>'
            );

            return $Call;
        });