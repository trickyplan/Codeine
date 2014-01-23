<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    setFn('Do', function ($Call)
    {
        F::Run('Entity', 'Create',
            [
                'Entity' => 'Notify',
                'Data!' => F::Live($Call['Notify'], $Call),
                'Session' => $Call['Session']
            ]);

        unset($Call['Notify']);

        return $Call;
    });