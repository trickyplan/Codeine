<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string($Call['Parsed'][0][$IX]);

            $Outer = '';
            $Inner = (string) $Root;

            $If = (bool) $Root->attributes()->condition;

            if ($If)
                $Outer = $Inner;

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });