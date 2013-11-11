<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply($Call['Run']['Service'], 'Before', $Call,
            isset($Call['Run']['Call'])? $Call['Run']['Call']: []);

        return $Call;
    });