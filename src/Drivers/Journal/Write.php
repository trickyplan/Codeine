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
                'Entity'    => 'Journal',
                'No Where'  => true,
                'Where!'    => null,
                'Data'      => $Journal
            ]);
        
        return $Call;
    });