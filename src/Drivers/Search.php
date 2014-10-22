<?php

    /* Codeine
     * @author BreathLess
     * @description Search Date
     * @package Codeine
     * @version 7.x
     */
    setFn('Index', function ($Call)
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
            if (isset($Call['Providers'][$Provider]))
            {
                $ProviderCall = $Call['Providers'][$Provider];
                $ProviderCall['Method'] = 'Index';
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
                if (isset($Call['Search']['Query']['Max']) && mb_strlen($Call['Query']) > $Call['Search']['Query']['Max'])
                    $Call['Query'] = mb_substr($Call['Query'], 0, $Call['Search']['Query']['Max']);

                if (isset($Call['Provider']))
                {
                    if (is_array($Call['Provider']))
                        $Providers = $Call['Provider'];
                    else
                        $Providers = [$Call['Provider']];
                }
                else
                {
                    $Providers = array_keys($Call['Providers']);
                    $Call['Provider'] = [];
                }

                $Call['Output']['Content'] = [];

                foreach ($Providers as $Provider)
                {
                    if (isset($Call['Providers'][$Provider]))
                    {
                        $Call['Output'][$Provider] = [];

                        $ProviderCall = $Call['Providers'][$Provider];

                        if (isset($ProviderCall['Non-vertical']) && $ProviderCall['Non-vertical'])
                        {
                            if (in_array($Provider, $Call['Provider']))
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
                $Call['Output']['Content'] = F::Sort($Call['Output']['Content'], 'Score', SORT_DESC);
            }

            if ($Call['Hits']['All'] == 0)
                $Call['Output']['Content'][] =
                [
                    'Type'  => 'Template',
                    'Scope' => 'Search',
                    'ID'    => 'Empty'
                ];
            else
                $Call['Output']['Content'] = F::Sort($Call['Output']['Content'], 'Score', SORT_DESC);

        $Call = F::Hook('afterSearchQuery', $Call);

        return $Call;
    });

    setFn('Remove', function ($Call)
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
            if (isset($Call['Providers'][$Provider]))
            {
                $ProviderCall = $Call['Providers'][$Provider];
                $ProviderCall['Method'] = 'Remove';
                F::Live($ProviderCall, $Call);
            }

        return $Call;
    });

