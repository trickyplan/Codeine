<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        $Result = null;
        if (empty($Call['Value']))
            F::Log('JSON: Empty', LOG_NOTICE);
        else
        {
            $Result = json_decode($Call['Value'], true);

            if (json_last_error() > 0)
            {
                F::Log('JSON: '.json_last_error_msg(), LOG_ERR);
                F::Log($Call['Value'], LOG_ERR);
            }
        }

        return $Result;
    });

    setFn('Write', function ($Call)
    {
        return j($Call['Value']);
    });

    setFn('Write.Call', function ($Call)
    {
        $Call['Value'] = j($Call['Value']);
        return $Call;
    });
