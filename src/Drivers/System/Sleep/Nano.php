<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        time_nanosleep($Call['Seconds'], $Call['Seconds'] - round($Call['Seconds'])*1000000000);
        return $Call;
    });