<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeList', function ($Call)
    {
        if (isset($Call['Filter']))
        {
            if (isset($Call['Nodes'][$Call['Filter']['Key']]['Filterable']) && $Call['Nodes'][$Call['Filter']['Key']]['Filterable'] == true)
                $Call['Where'][$Call['Filter']['Key']] = $Call['Filter']['Value'];
        }
                
        return $Call;
    });