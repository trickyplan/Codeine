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
                'Type' => 'Drivers',
                'Extension' => '.php'
            ]);
    });
    
    setFn('Get Drivers without Tests', function ($Call)
    {
        $Drivers = F::Run('Code.Reflection.Drivers', 'Enumerate', $Call);
        $Tests = F::Run('Code.Reflection.Tests', 'Enumerate', $Call);
        $NoTests = [];
        
        foreach ($Drivers as $Project => $ProjectDrivers)
            if (isset($Tests[$Project]))
            {
                $NoTests[$Project] = array_diff($ProjectDrivers, $Tests[$Project]);
                sort($NoTests[$Project]);
            }
                
        return $NoTests;
    });