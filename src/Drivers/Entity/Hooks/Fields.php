<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Process', function ($Call)
    {
        if (isset($Call['Fields']))
        {
            $Data = ['Partial!' => true];
            
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (in_array($Name, $Call['Fields']))
                    $Data = F::Dot($Data, $Name, F::Dot($Call['Data'],$Name));
                else
                    unset($Call['Nodes'][$Name]);
            }
            
            $Call['Data'] = $Data;
        }
        
        return $Call;
    });