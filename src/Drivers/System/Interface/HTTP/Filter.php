<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        foreach ($Call['HTTP']['Filter']['Methods'] as $Filter)
            if (F::Run('System.Interface.HTTP.Filter.'.$Filter, null, $Call) === true)
                ;
            else
            {
                F::Log('Filtered by '.$Filter, LOG_WARNING, 'Security');
                $Call = F::Hook('onHTTPFiltered', $Call);
            }
        
        return $Call;
    });