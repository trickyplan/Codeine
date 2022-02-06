<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Root = simplexml_load_string($Call['Parsed']['Match'][$IX]);

            $Inner = (string) $Root;
            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = idn_to_utf8($Inner);
        }
        return $Call;
    });