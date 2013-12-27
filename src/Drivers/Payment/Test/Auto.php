<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        F::Run('Entity', 'Update', $Call,
        [
            'Data' =>
            [
                'Balance' => $Call['Data']['Costs']
            ]
        ]);
        return $Call;
    });