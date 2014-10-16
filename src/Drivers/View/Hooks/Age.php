<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Data']['AgeLimit']) && isset($Call['Session']['User']['Age']) && $Call['Session']['User']['Age'] < $Call['Data']['AgeLimit'])
            $Call['Value'] = F::Run('View', 'Load', ['Scope' => 'Errors', 'ID' => 'Age', 'Data' => ['Level' => $Call['Data']['AgeLimit']]]);

        return $Call;
    });