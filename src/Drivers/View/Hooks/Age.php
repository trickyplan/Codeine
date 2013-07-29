<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Data']['Age']) && $Call['Session']['User']['Age'] < $Call['Data']['Age'])
            $Call['Value'] = F::Run('View', 'Load', ['Scope' => 'Errors', 'ID' => 'Age', 'Data' => ['Level' => $Call['Data']['Age']]]);

        return $Call;
    });