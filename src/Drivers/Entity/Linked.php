<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Count', function ($Call)
    {
        if (isset($Call['Data']['ID']))
        {
            if (!isset($Call['Key']))
                $Call['Key'] = $Call['Entity'];

            return F::Run('Entity', 'Count',
                [
                    'Entity' => $Call['Linked'],
                    'Where' =>
                    [
                        $Call['Key'] => $Call['Data']['ID']
                    ]
                ], ['Where' => $Call['Linked Where']]);
        }
        else
            return null;
    });