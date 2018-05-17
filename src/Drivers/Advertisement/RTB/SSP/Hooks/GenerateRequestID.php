<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeRTBRequest', function ($Call)
    {
        $ID = F::Run('Security.UID', 'Get', $Call, ['Mode' => F::Dot($Call, 'RTB.Client.Request.ID.Mode')]);
        F::Log('RTB Request ID generated: *'.$ID.'*', LOG_INFO);
       
        $Call = F::Dot($Call, 'RTB.Request.id', $ID);
        
        return $Call;
    });