<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Start', function ($Call)
    {
        return F::Run($Call['Profiling'], 'Start', $Call);
    });

    setFn('Finish', function ($Call)
    {
        return F::Run($Call['Profiling'], 'Finish', $Call);
    });