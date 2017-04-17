<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Skype Parslet
     * @package Codeine
     * @version 6.0
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
            if (isset($Call['Parsed']['Options'][$IX]['precision']))
                $Replaces[$IX] = round((float) $Match, $Call['Parsed']['Options'][$IX]['precision']);
            else
                $Replaces[$IX] = round((float) $Match);
        
        return $Replaces;
    });
    