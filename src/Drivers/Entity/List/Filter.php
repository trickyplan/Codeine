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
            foreach ($Call['Request']['Filter'] as $Key => $Value)
                if (empty($Value))
                    ;
                else
                {
                    if (isset($Call['Nodes'][$Key]['Filterable']) && $Call['Nodes'][$Key]['Filterable'])
                        $Call['Where'][$Key] = $Value;
                    else
                        F::Log('Filter by '.$Key.' not allowed', LOG_WARNING, 'Security');
                }
                
        return $Call;
    });