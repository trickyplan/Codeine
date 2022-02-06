<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Skype Parslet
     * @package Codeine
     * @version 6.0
     */
    
    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
            if (isset($Call['Parsed']['Options'][$IX]['precision']))
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = round((float) $Match, $Call['Parsed']['Options'][$IX]['precision']);
            else
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = round((float) $Match);
        
        return $Call;
    });
    