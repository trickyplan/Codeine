<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Run', function ($Call)
    {
        $Call = F::Hook('beforeJSONRPCRun', $Call);
        
        $Call['JSON-RPC']['Endpoint']           = $Call['Run']['Service'];
        $Call['JSON-RPC']['Request']['method']  = $Call['Run']['Method'];
        $Call['JSON-RPC']['Request']['params']  = $Call['Run']['Call'];
        $Call['JSON-RPC']['Request']['id']      = REQID.'.'.rand();
        
       $Call['JSON-RPC']['Response']  = F::Run('IO', 'Write',
            [
                'Storage'       => 'Web',
                'Where'         => $Call['JSON-RPC']['Endpoint'],
                'Format'        => 'Formats.JSON',
                'Output Format' => 'Formats.JSON',
                'IO One'        => true,
                'Data'          => $Call['JSON-RPC']['Request']
            ]);
        
        if (isset($Call['JSON-RPC']['Response']['result']))
            $Call['JSON-RPC']['Response'] = $Call['JSON-RPC']['Response']['result'];
        elseif (isset($Call['JSON-RPC']['Response']['error']))
        {
            F::Log($Call['JSON-RPC']['Response']['error'], LOG_ERR);
            $Call['JSON-RPC']['Response'] = null;
        }
        
        $Call = F::Hook('afterJSONRPCRun', $Call);
        
        return $Call['JSON-RPC']['Response'];
    });