<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (!empty($Call['Value']) && $Call['Purpose'] != 'Update')
            return F::Run('Security.Hash', 'Get',
                            [
                                'Mode' => 'Password',
                                'Value' => $Call['Value']
                            ]);
        else
            return $Call['Value'];
    });

    setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });