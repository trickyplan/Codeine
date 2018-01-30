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
                'Type' => 'Options',
                'Extension' => '.json'
            ]);
    });