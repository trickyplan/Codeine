<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeList', function ($Call)
    {
        if (isset($Call['Request']['Filter']))
        {
            F::Log('Filter String is *detected*', LOG_INFO);
            foreach ($Call['Request']['Filter'] as $Key => $Value)
                if (empty($Value))
                    ;
                else
                {
                    if (isset($Call['Nodes'][$Key]['Filterable']) && $Call['Nodes'][$Key]['Filterable'])
                    {
                        $Call['Where'][$Key] = $Value;
                        F::Log('Filter by *'.$Key.'* is *enabled*', LOG_INFO);
                    }
                    else
                        F::Log('Filter by *'.$Key.'* is *not allowed*', LOG_WARNING);
                }
        }
                
        return $Call;
    });