<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
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

    setFn('Ratio', function ($Call)
    {
        $Sum = $Call['Data']['VoteFor']+$Call['Data']['VoteAgainst'];
        if ($Sum == 0)
            return 100;
        else
            return ($Call['Data']['VoteFor'] / $Sum);
    });