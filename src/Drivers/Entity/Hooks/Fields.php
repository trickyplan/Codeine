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
            $Defined = count($Call['Nodes']);

            $Values = [];

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (in_array($Name, $Call['Fields']))
                    $Values = F::Dot($Values, $Name, F::Dot($Call['Data'],$Name));
                else
                    unset($Call['Nodes'][$Name]);
            }

            $Call['Data'] = $Values;
            $Call['Data']['_NodesDefined']  = $Defined;
            $Call['Data']['_NodesLoaded']   = count($Call['Fields']);
            $Call['Data']['_Partial']       = $Call['Data']['_NodesDefined'] != $Call['Data']['_NodesLoaded'];
        }
        
        return $Call;
    });