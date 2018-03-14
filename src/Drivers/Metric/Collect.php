<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Result = [];
        foreach ($Call['Metric']['Collect']['Collectors'] as $Name => $Collector)
        {
            $Result[$Name] = F::Live($Collector['Run'], $Call);
            
            F::Run('Metric', 'Set', $Call,
                [
                    'Metric' =>
                    [
                        'Type'      => $Collector['Type'],
                        'Value'     => $Result[$Name]
                    ]
                ]);
        }
        
        return $Result;
    });