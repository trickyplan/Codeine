<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('RTB.SSP.Request.Executed', function ($Call)
    {
        $SelectedResults = [];
        $Results = F::Dot($Call, 'RTB.Result');
        
        foreach ($Results as $Call['DSP']['Name'] => $cResult)
        {
            if (empty($cResult))
            {
               F::Log('Empty RTB response from ' . $Call['DSP']['Name'], LOG_NOTICE, 'RTB');
               continue;
            }
    
            if (empty($cResult['seatbid']))
            {
                F::Log('No seatbid section in RTB response from '.$Call['DSP']['Name'], LOG_NOTICE, 'RTB');
                continue;
            }
            
            F::Hook('RTB.SSP.Request.Fulfilled', $Call);
            $SelectedResults[$Call['DSP']['Name']] = $cResult;
        }
        
        $Call = F::Dot($Call, 'RTB.Result', $SelectedResults);
        
        return $Call;
    });