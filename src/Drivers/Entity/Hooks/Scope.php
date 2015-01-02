<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['Substitute']))
            $Call['Scope'] = $Call['Substitute'];
        else
            $Call['Scope'] = $Call['Entity'];

        return $Call;
    });