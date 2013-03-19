<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call = F::Hook('beforeCheck', $Call);

            F::Run('Entity', 'Update', $Call,
            [
                'One' => true,
                'Data' =>
                [
                    'Check' => true
                ]
            ]);


        $Call = F::Hook('afterCheck', $Call);

        return $Call;
    });