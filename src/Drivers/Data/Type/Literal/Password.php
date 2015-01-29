<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });