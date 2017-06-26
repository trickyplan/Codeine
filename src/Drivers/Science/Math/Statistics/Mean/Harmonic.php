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
        
        $Sum = 0;
        
        foreach ($Slice as $Value)
            $Sum += 1/$Value;
        
        $Count = count($Slice);
        if ($Count > 0)
            return $Count / $Sum;
        else
            return null;
    });