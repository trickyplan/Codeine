<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        d(__FILE__, __LINE__, $Call['Parsed']);

        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string($Call['Parsed']['Match'][$IX]);

            $Inner = (string) $Root;
            $Outer = idn_to_utf8($Inner);
            d(__FILE__, __LINE__, $Inner);
            d(__FILE__, __LINE__, $Outer);


            $Call['Output'] = str_replace ($Call['Parsed']['Match'][$IX], $Outer, $Call['Output']);
        }
        return $Call;
    });