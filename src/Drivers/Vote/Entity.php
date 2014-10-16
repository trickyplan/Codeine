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
                              'Entity' => 'Vote',
                              'Where'  =>
                              [
                                  'Type' => $Call['Entity'],
                                  'Object' => $Call['Data']['ID'],
                                  'Direction'  => $Call['Name']
                              ]
                        ]);
        else
            return 0;
    });