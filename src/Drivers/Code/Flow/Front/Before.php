<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Run']['Service']))
            $Call = F::Apply($Call['Run']['Service'], 'Before', $Call,
                isset($Call['Run']['Call'])? $Call['Run']['Call']: []);

        return $Call;
    });