<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (isset($Call['Parsed']['Options'][$IX]['eq'] ))
            {
                if ($Call['Parsed']['Options'][$IX]['eq'] == F::Environment())
                    $Replaces[$IX] = $Match;
                else
                    $Replaces[$IX] = '';
            }
            
            $NotEnvironment = isset($Call['Parsed']['Options'][$IX]['neq']) ? $Call['Parsed']['Options'][$IX]['neq'] : null;
            
            if ($NotEnvironment !== null)
            {
                if ($NotEnvironment == F::Environment())
                    $Replaces[$IX] = '';
                else
                    $Replaces[$IX] = $Match;
            }
        }
        
        return $Replaces;
    });