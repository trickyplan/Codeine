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
        {
            if (is_array($Call['Provider']))
                $Providers = $Call['Provider'];
            else
                $Providers = [$Call['Provider']];
        }
        else
            $Providers = array_keys($Call['Providers']);

        foreach ($Providers as $Provider)
        {
            $ProviderCall = $Call['Providers'][$Provider];
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
        {
            if (is_array($Call['Provider']))
                $Providers = $Call['Provider'];
            else
                $Providers = [$Call['Provider']];
        }
        else
            $Providers = array_keys($Call['Providers']);

        $Call['Output']['Content'] = [];

        foreach ($Providers as $Provider)
        {
            $Call['Output'][$Provider] = [];

            $ProviderCall = $Call['Providers'][$Provider];
            $ProviderCall['Method'] = 'Query';

            $Results = F::Live($ProviderCall, $Call);

            $Call['Output'][$Provider] = F::Merge($Call['Output'][$Provider], $Results);
        }

        return $Call;
    });


