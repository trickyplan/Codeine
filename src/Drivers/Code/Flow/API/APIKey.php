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
        
        if (isset($Call['Request']['APIKey']))
        {
            $Call['API']['User'] = F::Run('Entity', 'Read',
                [
                    'Entity'    => 'User',
                    'Where'     =>
                    [
                        'APIKey' => $Call['Request']['APIKey']
                    ],
                    'One'       => true
                ]);
            
            if (empty($Call['API']['User']))
                ;
            else
            {
                $Call['API']['Request']['Valid Key'] = true;
                $Call['API']['Request']['User'] = $Call['API']['User']['ID'];
            }
        }
        
        return $Call;
    });