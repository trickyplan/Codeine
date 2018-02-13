<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $ID = F::Run('Security.UID', 'Get', $Call, ['Mode' => F::Dot($Call, 'RTB.Impression.ID.Mode')]);
        
        $Call = F::Dot($Call, 'RTB.DSP.Impression.id', $ID);
        F::Log('RTB Impression ID generated: *'.$ID.'*', LOG_INFO);

        $Call = F::Hook('beforeBannerAdd', $Call);
        
            if (($Banner = F::Dot($Call, 'RTB.DSP.Banner')) == null)
                ;
            else
                $Call['RTB']['DSP']['Impression']['banner'] = array_merge($Call['RTB']['DSP']['Impression']['banner'], $Banner);
        
        $Call = F::Hook('afterBannerAdd', $Call);

        $Call['RTB']['DSP']['Request']['imp'][] = $Call['RTB']['DSP']['Impression'];

        return $Call;
    });