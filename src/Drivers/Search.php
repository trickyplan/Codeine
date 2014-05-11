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
        $Call = F::Hook('beforeSearchQuery', $Call);

            $Call['Layouts'][] = ['Scope' => '','ID' => 'Search'];

            if (isset($Call['Request']['Query']))
                $Call['Query'] = $Call['Request']['Query'];

            if (isset($Call['Query']))
            {
                if (isset($Call['Provider']) && isset($Providers[$Call['Provider']]))
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

                    if (isset($ProviderCall['Non-vertical']) && $ProviderCall['Non-vertical'])
                    {
                        if (isset($Call['Provider']) && $Call['Provider'] == $Provider)
                            ;
                        else
                            continue;
                    }

                    $ProviderCall['Method'] = 'Query';

                    $Results = F::Live($ProviderCall, $Call);

                    if (isset($Results['Meta']))
                        $Call = F::Merge($Call, $Results['Meta']);

                    if (isset($Results['SERP']))
                    {
                        $Call['Output']['Content'] = F::Merge($Call['Output']['Content'], $Results['SERP']);
                        $Call['Output'][$Provider] = F::Merge($Call['Output'][$Provider], $Results['SERP']);
                    }

                    $Call['Hits']['All'] += $Results['Meta']['Hits'][$Provider];
                }
            }

            if ($Call['Hits']['All'] == 0)
                $Call['Output']['Content'][] =
                [
                    'Type'  => 'Template',
                    'Scope' => 'Search',
                    'ID'    => 'Empty'
                ];

        $Call = F::Hook('afterSearchQuery', $Call);

        return $Call;
    });


