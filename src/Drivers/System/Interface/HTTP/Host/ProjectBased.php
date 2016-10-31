<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (is_array($Call['Project']['Hosts'][F::Environment()]))
            $Hosts = $Call['Project']['Hosts'][F::Environment()];
        else
            $Hosts = [$Call['Project']['Hosts'][F::Environment()]];
        
        // Select Default Host
        if (isset($_SERVER['HTTP_HOST']))
        {
            if (in_array($_SERVER['HTTP_HOST'], $Hosts))
                $Call['HTTP']['Host'] = $_SERVER['HTTP_HOST'];
        }
        
        if (isset($Call['HTTP']['Host']))
            F::Log('Host is determined: *'.$Call['HTTP']['Host'].'*', LOG_INFO);
        else
        {
            $Call['HTTP']['Host'] = $Hosts[0];
            F::Log('Host is not determined, default selected', LOG_WARNING);
        }
                
        if (isset($Call['Project']['Active Hosts'][$Call['HTTP']['Host']]))
        {
            $Call = F::Merge($Call, $Call['Project']['Active Hosts'][$Call['HTTP']['Host']]);
            F::Log('Active host loaded: *'.$Call['HTTP']['Host'].'*', LOG_INFO);
        }
        
        return $Call;
    });