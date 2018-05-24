<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('RTB.SSP.Request.Executed', function ($Call)
    {
        $Converted = [];
        $Results = F::Dot($Call, 'RTB.Result');
        
        $DSPs = F::Dot($Call, 'AdRam.Fetch.RTB.DSP.Selected');
        
        foreach ($DSPs as $Name => $DSP)
            if (null !== ($CC = F::Dot($Results, $DSP['Endpoint'])))
                $Converted[$Name] = $CC;
        
        $Call = F::Dot($Call, 'RTB.Result', $Converted);
        
        return $Call;
    });