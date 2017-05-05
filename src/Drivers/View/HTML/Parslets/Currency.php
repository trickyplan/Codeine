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
            $Currency = '';
            
            if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.from'))
            {
                $Call['From'] = F::Dot($Call['Parsed'], 'Options.' . $IX . '.from');
                
                if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.to'))
                    $Call['Currency']['To'] = F::Dot($Call['Parsed'], 'Options.' . $IX . '.to');
                
                if (F::Dot($Call['Parsed'], 'Options.' . $IX . '.precision'))
                    $Call['Currency']['Precision'] = F::Dot($Call['Parsed'], 'Options.' . $IX . '.precision');
                
                $Match = strtr($Match, ',', '.');
                
                if (is_numeric($Match))
                {
                    $Currency = F::Run('Finance.Currency', 'Rate.Convert',
                        [
                            'From'  => $Call['From'],
                            'To'    => $Call['Currency']['To'],
                            'Value' => $Match
                        ]);
                    
                } else
                    F::Log('Currency convert failed: ' . $Match, LOG_ERR);
            }
            
            $Replaces[$IX] = $Currency;
        }
        
        return $Replaces;
    });