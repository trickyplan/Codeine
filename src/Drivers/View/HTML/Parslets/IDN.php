<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string($Call['Parsed']['Match'][$IX]);

            $Inner = (string) $Root;
            $Replaces[$IX] = idn_to_utf8($Inner);
        }
        return $Replaces;
    });