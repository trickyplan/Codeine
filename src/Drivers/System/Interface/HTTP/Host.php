<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeHostDetect', $Call);
        
            F::Log('Host Strategy *'.$Call['HTTP']['Host Strategy'].'* selected', LOG_INFO);
            $Call = F::Apply($Call['HTTP']['Host Strategy'], 'Do', $Call);
    
            if ($Host = F::Dot($Call, 'HTTP.Host'))
            {
                if (isset($Call['HTTP']['Domain']))
                    ;
                else
                    $Call['HTTP']['Domain'] = $Host;
        
                F::Log('Host is *'.$Host.'*', LOG_INFO);
                $Call = F::loadOptions($Host, null, $Call);
            }
            
        $Call = F::Hook('afterHostDetect', $Call);
        
        return $Call;
    });