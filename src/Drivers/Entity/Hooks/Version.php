<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Generate', function ($Call)
    {
        return $Call['Data']['Version']+1;
    });

    setFn('Where', function ($Call)
    {
        $Call['Sort']['Version'] = false;
        return $Call;
    });