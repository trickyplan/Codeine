<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('Test', function ($Call)
    {
        if ($Code = F::Dot($Call, 'System.Interface.HTTP.Codes.'.$Call['HTTP Code']))
            $Call['HTTP']['Headers']['HTTP/1.1'] = $Call['HTTP Code'].' '.$Code;

        return $Call;
    });