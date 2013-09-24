<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

            $Environment = isset($Root->attributes()->eq)? (string) $Root->attributes()->eq: 'Production';

            if ($Environment == F::Environment())
                $Outer = $Match;
            else
                $Outer = '';


            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
     });