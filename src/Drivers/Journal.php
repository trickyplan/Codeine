<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Write', function ($Call)
    {
        F::Run('Entity', 'Create',
        [
            'Entity' => 'Journal',
            'Data'   =>
                [
                    'Entity' => $Call['Entity'],
                    'Event' => $Call['Event']
                ]
        ]);

        return $Call;
    });

