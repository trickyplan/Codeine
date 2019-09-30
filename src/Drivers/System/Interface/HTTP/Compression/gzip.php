<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('Do', function ($Call)
    {
        $Call = F::Dot($Call, 'HTTP.Headers.Content-Encoding:', 'gzip');
        $Call['Output'] = F::Run('Data.Compress.GZIP', 'Write', ['Data' => $Call['Output']]);
        return $Call;
    });