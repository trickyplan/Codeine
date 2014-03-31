<?php

    /* Codeine
     * @author BreathLess
     * @description Search Date
     * @package Codeine
     * @version 7.x
     */
    setFn('Add', function ($Call)
    {
        if (isset($Call['Provider']))
            $Providers = [$Call['Provider'] => $Call['Providers'][$Call['Provider']]];
        else
            $Providers = $Call['Providers'];

        foreach ($Providers as $ProviderCall)
        {
            $ProviderCall['Method'] = 'Add';
            F::Live($ProviderCall, $Call);
        }

        return $Call;
    });

    setFn('Query', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => '','ID' => 'Search'];

        if (isset($Call['Request']['Query']))
            $Call['Query'] = $Call['Request']['Query'];

        if (isset($Call['Provider']))
            $Providers = [$Call['Provider'] => $Call['Providers'][$Call['Provider']]];
        else
            $Providers = $Call['Providers'];

        $Call['Output']['Content'] = [];

        foreach ($Providers as $ProviderCall)
        {
            $ProviderCall['Method'] = 'Query';

            $Results = F::Live($ProviderCall, $Call);

            $Call['Output']['Content'] = F::Merge($Call['Output']['Content'], $Results);
        }

        return $Call;
    });


