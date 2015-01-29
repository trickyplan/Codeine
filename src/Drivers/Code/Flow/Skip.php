<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        if (($Result = F::Live($Call['Run'])) === null)
            return null;
        else
            return $Result;
    });