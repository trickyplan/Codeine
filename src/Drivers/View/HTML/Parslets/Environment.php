<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
         
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string('<root '.$Call['Parsed']['Options'][$IX].'></root>');

            $Environment = isset($Root->attributes()->eq)? (string) $Root->attributes()->eq: null;

            if ($Environment == F::Environment())
                $Outer = $Match;
            else
                $Outer = '';

            $NotEnvironment = isset($Root->attributes()->neq)? (string) $Root->attributes()->neq: null;

            if ($NotEnvironment !== null)
            {
                if ($NotEnvironment == F::Environment())
                    $Outer = '';
                else
                    $Outer = $Match;
            }

            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Outer, $Call['Output']);
        }

        return $Call;
     });