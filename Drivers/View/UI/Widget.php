<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Add', function ($Call)
        {
            $Widget = $Call['Widget'];

            if (!isset($Widget['Place']))
                $Widget['Place'] = 'Content';

            $Call['Widgets'][] = $Widget;

            return $Call;
        });