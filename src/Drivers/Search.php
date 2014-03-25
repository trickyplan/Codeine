<?php

    /* Codeine
     * @author BreathLess
     * @description Search Date
     * @package Codeine
     * @version 7.x
     */

    setFn('Query', function ($Call)
    {
        if (isset($Call['Request']['Query']))
            $Call['Query'] = $Call['Request']['Query'];

        if (isset($Call['Provider']))
            $Providers = [$Call['Provider'] => $Call['Providers'][$Call['Provider']]];
        else
            $Providers = $Call['Providers'];

        $Call['Output']['Content'] = [];

        foreach ($Providers as $ProviderName => $ProviderCall)
        {
            $Results = F::Live($ProviderCall, $Call);

            $Call['Output']['Content'] = F::Merge($Call['Output']['Content'], $Results);
            sort($Call['Output']['Content']);
        }

        return $Call;
    });


