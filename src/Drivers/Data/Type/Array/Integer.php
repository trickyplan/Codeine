<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            $Value = (int) $Value;

        return $Call['Value'];
    });

    setFn(['Read','Where'], function ($Call)
    {
        $Call['Value'] = (array) $Call['Value'];

        foreach ($Call['Value'] as &$Value)
            if (is_scalar($Value))
                $Value = (int) $Value;
            else
                F::Log('Non-scalar value *'.j($Value).'*, skipped', LOG_NOTICE);

        return $Call['Value'];
    });