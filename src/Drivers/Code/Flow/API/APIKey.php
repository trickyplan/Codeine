<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeAPIRun', function ($Call)
    {
        $Call['API']['Request']['Valid Key'] = false;
        $APIKey = null;
        
        if (F::Dot($Call, 'HTTP.Request.Headers.X-Apikey'))
            $APIKey = F::Dot($Call, 'HTTP.Request.Headers.X-Apikey');
        else
            $APIKey = F::Dot($Call, 'Request.APIKey');
        
        if ($APIKey !== null)
        {
            $Call['Session']['User'] = F::Run('Entity', 'Read',
                [
                    'Entity'    => 'User',
                    'Where'     =>
                    [
                        'APIKey' => $APIKey
                    ],
                    'One'       => true
                ]);
            
            if (empty($Call['Session']['User']))
                ;
            else
            {
                $Call['API']['Request']['Valid Key'] = true;
                $Call['API']['Request']['User'] = $Call['Session']['User']['ID'];
            }
        }
        
        return $Call;
    });