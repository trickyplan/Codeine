<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string($Call['Parsed']['Match'][$IX]);

            $Outer = '';
            $Inner = (string) $Root;

            $From = (int) $Root->attributes()->from;
            $To = (int) $Root->attributes()->to;

            for($i = $From; $i <= $To; $i++)
                $Outer.= str_replace('<num/>', $i, $Inner);

            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });