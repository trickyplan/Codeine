<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Calculate', function ($Call)
    {
        if (F::Dot($Call, 'Window') > 0)
            $Slice = array_slice($Call['Values'], -$Call['Window'], $Call['Window']);
        else
            $Slice = $Call['Values'];
        
        $Sum = 1;
        
        foreach ($Slice as $Value)
            $Sum *= $Value;
        
        return $Sum ** (1/count($Slice));
    });