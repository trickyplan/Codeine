<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Try = 0;
        $Result = null;

        do
        {
            $Result = F::Live($Call['Run']);
            $Try++;
        }
        while (($Result === null) or $Try == $Call['Try']);

        return $Result;
    });