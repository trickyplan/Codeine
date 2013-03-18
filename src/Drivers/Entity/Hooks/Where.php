<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeOperation', function ($Call)
    {
        // Если в Where скалярная переменная - это ID.
        if (isset($Call['Where']))
            if (is_scalar($Call['Where']))
                $Call['Where'] = ['ID' => $Call['Where']];
            else
                $Call['Where'] = F::Live($Call['Where']);

        return $Call;
    });