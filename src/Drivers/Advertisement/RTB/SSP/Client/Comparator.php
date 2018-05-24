<?php

    setFn('RTB.SSP.Request.Executed', function ($Call)
    {
        $Call = F::Hook('beforeSSPClientComparator', $Call);
        
            $Call = F::Run('Advertisement.RTB.SSP.Client.Comparator.'.F::Dot($Call, 'RTB.SSP.Client.Comparator.Strategy'), 'Do', $Call);
            
        $Call = F::Hook('afterSSPClientComparator', $Call);
        
        return $Call;
    });