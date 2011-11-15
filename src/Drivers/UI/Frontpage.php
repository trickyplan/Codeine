<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Do', function ($Call)
    {
        $Call['Widgets'][] = array(
                    'Place' => 'Content',
                    'Type' => 'Layout',
                    'ID' => 'UI/Frontpage'
                );

        return $Call;
     });