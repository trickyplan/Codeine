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
            $Format = F::Dot($Call['Parsed'], 'Options.'.$IX.'.format') ? F::Dot($Call['Parsed'], 'Options.'.$IX.'.format'): 'French';
            $Digits = F::Dot($Call['Parsed'], 'Options.'.$IX.'.digits') ? F::Dot($Call['Parsed'], 'Options.'.$IX.'.digits') : 0;
            
            $Match = trim($Match);
            
            if (is_scalar($Match) && isset($Format) && !empty($Match))
            {
                $Match = strtr($Match, ',', '.');
                switch ($Format)
                {
                    case 'French':
                        $Replaces[$IX] = F::Run('Formats.Number.French', 'Do', ['Value' => $Match, 'Digits' => $Digits]);
                        break;
                    
                    case 'English':
                        $Replaces[$IX] = number_format($Match, $Digits);
                        break;
                    
                    case 'Sprintf':
                        $Sprintf = F::Dot($Call['Parsed'], 'Options.'.$IX.'.sprintf') ? F::Dot($Call['Parsed'], 'Options.'.$IX.'.sprintf') : '%d';
                        $Replaces[$IX] = F::Run('Formats.Number.Sprintf', 'Do', ['Value' => $Match, 'Format' => $Sprintf]);
                        break;
                    
                    default:
                        $Replaces[$IX] = sprintf($Format, $Match);
                        break;
                }
            } else
                $Replaces[$IX] = $Match;
        }
        
        return $Replaces;
    });