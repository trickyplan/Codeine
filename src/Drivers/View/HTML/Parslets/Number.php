<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Parse', function ($Call)
     {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<number'.$Call['Parsed'][1][$IX].'></number>');

            $Format = isset($Root->attributes()->format)? (string) $Root->attributes()->format: 'French';

            switch($Format)
            {
                case 'French':
                    $Outer = number_format($Match, 2, ',', ' ');
                break;

                case 'English':
                    $Outer = number_format($Match);
                break;
            }

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
     });