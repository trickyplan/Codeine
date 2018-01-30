<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    
    setFn('Enumerate', function ($Call)
    {
        return F::Run('Code.Reflection', 'Enumerate Files', $Call,
            [
                'Type' => 'Tests',
                'Extension' => '.json'
            ]);
    });
    
    setFn('Calculate Coverage', function ($Call)
    {
        $Drivers = F::Run('Code.Reflection.Drivers', 'Enumerate', $Call);
        $Tests = F::Run('Code.Reflection.Tests', 'Enumerate', $Call);
    
        foreach ($Drivers as $Project => $ProjectDrivers)
        {
            $Coverage[$Project] =
            [
                'Drivers' => count($ProjectDrivers)
            ];
            
            if (isset($Tests[$Project]))
            {
                $Coverage[$Project]['Tests'] = count($Tests[$Project]);
                $Coverage[$Project]['Covered'] = count(array_intersect($ProjectDrivers, $Tests[$Project]));
                $Coverage[$Project]['Percentage'] = round(($Coverage[$Project]['Covered']/$Coverage[$Project]['Drivers'])*100, 2);
            }
        }
        
        return $Coverage;
    });