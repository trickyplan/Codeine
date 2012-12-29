<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Count', function ($Call)
    {
        if (isset($Call['Data']['ID']))
            return F::Run('Entity', 'Count',
                    [
                         'Entity' => $Call['Linked'],
                         'Where' =>
                         [
                            $Call['Entity'] => $Call['Data']['ID']
                         ]
                    ]);
        else
            return null;
    });