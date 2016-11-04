<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('External', function ($Call)
    {
        return F::Run(null, 'Internal', $Call,
            [
                'Message'   => $Call['Request']['Message'],
                'URL'   => $Call['Request']['URL']
            ]);
    });
    
    setFn('Internal', function ($Call)
    {
        $Error = F::Run('Entity', 'Create', $Call,
            [
                'Entity' => 'Error',
                'Data'   =>
                [
                    'Title' => $Call['Message'],
                    'Call'  => $Call,
                    'URL'   => $Call['URL']
                ]
            ]);
        
        return $Call;
    });