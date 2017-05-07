<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        $Output = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Cludge)
        {
            $From = $Call['Parsed']['Options'][$IX]['from'];
            $To = $Call['Parsed']['Options'][$IX]['to'];
            $Increment = isset($Call['Parsed']['Options'][$IX]['increment'])? $Call['Parsed']['Options'][$IX]['increment']: 1;
            
            for ($Index = $From; $Index < $To; $Index+= $Increment)
                $Output[$Index] = str_replace('<repeat-index/>', $Index, $Cludge);
            
            $Output = implode('', $Output);
            
            $Replaces[$IX] = $Output;
        }

        return $Replaces;
     });