<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Journal = F::Live($Call['Journal'], $Call);
        
        F::Run('Entity', 'Create', $Call,
            [
                'Entity' => 'Journal',
                'Where!'  => null,
                'Data'   => $Journal
            ]);
        
        return $Call;
    });