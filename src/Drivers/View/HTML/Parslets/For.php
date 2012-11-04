<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $Root = simplexml_load_string($Call['Parsed'][0][$IX]);

            $Outer = '';
            $Inner = (string) $Root;

            $From = (int) $Root->attributes()->from;
            $To = (int) $Root->attributes()->to;

            for($i = $From; $i <= $To; $i++)
                $Outer.= str_replace('<num/>', $i, $Inner);

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });