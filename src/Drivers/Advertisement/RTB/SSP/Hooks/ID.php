<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $ID = F::Run('Security.UID', 'Get', $Call, ['Mode' => F::Dot($Call, 'RTB.Request.ID.Mode')]);

        $Call = F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) use ($ID) { 
            return F::Dot($DSP, 'Request.id', $ID);
        }, F::Dot($Call, 'RTB.DSP.Items')));
        
        F::Log('RTB Request ID generated: *'.$ID.'*', LOG_INFO);
        
        return $Call;
    });
