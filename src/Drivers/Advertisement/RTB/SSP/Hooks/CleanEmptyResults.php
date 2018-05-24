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
        
        foreach ($Results as $From => $cResult)
        {
            if (empty($cResult))
            {
               F::Log('Empty RTB response from ' . $From, LOG_NOTICE, 'RTB');
               continue;
            }
    
            if (empty($cResult['seatbid']))
            {
                F::Log('No seatbid section in RTB response from '.$From, LOG_NOTICE, 'RTB');
                continue;
            }
            
            $SelectedResults[$From] = $cResult;
        }
        
        $Call = F::Dot($Call, 'RTB.Result', $SelectedResults);
        
        return $Call;
    });