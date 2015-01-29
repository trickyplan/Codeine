<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Current']))
            $Call['Data'] = F::Merge($Call['Current'], $Call['Data']);

        return $Call;
    });