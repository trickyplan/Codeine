<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Add', function ($Call)
        {
            $Widget = $Call['Widget'];

            if (!isset($Widget['Place']))
                $Widget['Place'] = 'Content';

            $Call['Widgets'][] = $Widget;

            return $Call;
        });