<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Bundle']) && in_array($Call['Bundle'], $Call['Bundles']))
            return F::Run(null, 'Bundle', $Call);
        else
            return F::Run(null, 'All', $Call);
    });

    setFn('Bundle', function ($Call)
    {
        if (!isset($Call['Sensor']))
            $Call['Sensor'] = 'Do';

        $Call['Output'] =  F::Run($Call['Bundle'].'.Monitor', $Call['Sensor'], $Call);

        return $Call;
    });

    setFn('All', function ($Call)
    {
        foreach ($Call['Bundles'] as $Bundle)
            $Call['Output'][$Bundle] = F::Run($Bundle.'.Monitor', 'Do', $Call);

        return $Call;
    });