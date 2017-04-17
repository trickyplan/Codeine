<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (F::Dot($Call['Parsed'],'Options.'.$IX.'.type') === null)
                $Type = 'XML';
            else
                $Type = F::Dot($Call['Parsed'],'Options.'.$IX.'.type');
            
            $Match = F::Run('Formats.' . $Type, 'Read', ['Value' => trim($Call['Parsed']['Value'][$IX])]);
            
            foreach ($Call['Inherited'] as $Key)
                if (isset($Call[$Key]))
                    $Match['Call'][$Key] = $Call[$Key];
            
            $Output = F::Live($Match);
            
            // FIXME Add Return Key
            if (is_array($Output))
                $Output = '{}';
            
            if (is_float($Output))
                $Output = str_replace(',', '.', $Output);
            
            $Replaces[$IX] = $Output;
        }
        
        return $Replaces;
    });