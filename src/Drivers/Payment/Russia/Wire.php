<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Receiver'] = F::Run('Entity', 'Read', $Call,
        [
            'Entity' => 'Company',
            'Where'  => 1,
            'One'    => true
        ]);

        return $Call;
    });