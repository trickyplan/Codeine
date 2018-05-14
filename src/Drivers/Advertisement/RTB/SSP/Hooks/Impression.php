<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeRTBRequest', function ($Call)
    {
        $Call = F::Apply(null, 'Generate Impression ID', $Call);

        $Call = F::Hook('beforeBannerAdd', $Call);
        
            if (($Banner = F::Dot($Call, 'RTB.DSP.Banner')) == null)
                ;
            else
                $Call['RTB']['DSP']['Impression']['banner'] = array_merge($Call['RTB']['DSP']['Impression']['banner'], $Banner);
        
        $Call = F::Hook('afterBannerAdd', $Call);

        $Call['RTB']['DSP']['Request']['Impression'][] = $Call['RTB']['DSP']['Impression'];

        return $Call;
    });
    
    setFn('Generate Impression ID', function ($Call)
    {
        $ID = F::Run('Security.UID', 'Get', $Call, ['Mode' => F::Dot($Call, 'RTB.Impression.ID.Mode')]);
        
        $Call = F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) use ($ID) {
            $DSP['Impression']['id'] = $ID;
            return $DSP;
        }, F::Dot($Call, 'RTB.DSP.Items')));

        F::Log('RTB Impression ID generated: *'.$ID.'*', LOG_INFO);
        
        return $Call;
    });