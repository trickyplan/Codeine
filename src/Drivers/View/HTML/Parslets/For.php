<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        $Replaces = [];
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string($Call['Parsed']['Match'][$IX]);

            $For = '';
            $Inner = (string) $Root;

            $From = (int) $Root->attributes()->from;
            $To = (int) $Root->attributes()->to;

            for($i = $From; $i <= $To; $i++)
                $For.= str_replace('<num/>', $i, $Inner);

            $Replaces[$IX] = $For;
        }

        return $Replaces;
    });