<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Company.Bank.Name', function ($Call)
    {
        if (isset($Call['Data']['Company']['Bank']['Code']))
            return
            F::Run('Government.Russia.CBR',
                'Code2Name',
                ['Value' => $Call['Data']['Company']['Bank']['Code']]);
        else
            return null;
    });