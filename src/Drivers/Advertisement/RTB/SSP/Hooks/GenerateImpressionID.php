<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeRTBRequest', function ($Call)
    {
        $ID = F::Run('Security.UID', 'Get', $Call, ['Mode' => F::Dot($Call, 'RTB.Client.Impression.ID.Mode')]);
        F::Log('RTB Impression ID generated: *'.$ID.'*', LOG_INFO);
       
        $Call = F::Dot($Call, 'RTB.Impression.ID', $ID);
        
        return $Call;
    });