<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['Substitute']))
            $Call['Scope'] = $Call['Substitute'];

        return $Call;
    });