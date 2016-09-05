<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Value'] = F::Run('View', 'Load', $Call, ['Context' => 'txt']);
        return $Call;
    });