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
        
            $ImpressionDefaults = F::Dot($Call, 'RTB.DSP.Impression Defaults');
            
            $Call = F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) use ($ImpressionDefaults) {
                if (F::Dot($DSP, 'Banner') == null)
                    ;
                else
                    $DSP['Impression']['banner'] = array_merge($DSP['Impression']['banner'] ?? $ImpressionDefaults['banner'], $DSP['Banner']);
    
                return $DSP;
            }, F::Dot($Call, 'RTB.DSP.Items')));
        
        $Call = F::Hook('afterBannerAdd', $Call);

        $Call = F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) {
            $DSP['Request']['imp'][] = $DSP['Impression'];
            return $DSP;
        }, F::Dot($Call, 'RTB.DSP.Items')));

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