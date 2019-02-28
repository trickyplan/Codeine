<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Status = opcache_get_status(true);
        
        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'console',
                'Value' => j($Status)
            ];

        return $Call;
    });