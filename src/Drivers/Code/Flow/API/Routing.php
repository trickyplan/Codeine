<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeAPIRun', function ($Call)
    {
        if (preg_match('@/api/(?P<Version>.+)/(?P<Format>.+)/(?P<Service>.+)/(?P<Method>.+)@Ssu', $Call['HTTP']['URL'], $Pockets))
        {
            $Call['API']['Request']['ID'] = RequestID;
            $Call['API']['Request']['Format'] = $Pockets['Format'];
            $Call['API']['Request']['Version'] = $Pockets['Version'];
            $Call['API']['Request']['Service'] = $Pockets['Service'];
            $Call['API']['Request']['Method'] = $Pockets['Method'];
        }
        else
            $Call['API']['Request'] = null;
        
        return $Call;
    });