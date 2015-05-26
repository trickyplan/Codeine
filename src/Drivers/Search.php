<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
    setFn('Add', function ($Call)
    {
        if (isset($Call['Search']['Provider'][$Call['Provider']]))
            $Call = F::Apply($Call['Providers'][$Call['Provider']]['Driver'], null, $Call['Providers'][$Call['Provider']], $Call);
        return $Call;
    });

    setFn('Remove', function ($Call)
    {
        if (isset($Call['Search']['Provider'][$Call['Provider']]))
            $Call = F::Apply($Call['Providers'][$Call['Provider']]['Driver'], null, $Call['Providers'][$Call['Provider']], $Call);

        return $Call;
    });

    setFn('Query', function ($Call)
    {
        if (isset($Call['Request']['Query']))
            $Call['Query'] = $Call['Request']['Query']; // FIXME

        $Call = F::Hook('beforeQuery', $Call);

        $Call['Layouts'][] = ['Scope' => '','ID' => 'Search'];
        $Call['Output']['Content'] = [];
        $Call['Hits'] = ['All' => 0];

        if (isset($Call['Provider']))
        {
            if (is_array($Call['Provider']))
                ;
            else
                $Call['Provider'] = (array) $Call['Provider'];
        }
        else
            $Call['Provider'] = array_keys($Call['Search']['Provider']); // Vertical

        foreach ($Call['Search']['Provider'] as $Provider => $ProviderCall)
        {
            if (isset($ProviderCall['Non-vertical']))
                ;
            else
            {
                $Result = F::Run($ProviderCall['Driver'], 'Query', $ProviderCall, $Call);
                $Call['Hits'] = F::Dot($Call['Hits'], $Provider, $Result['Meta']['Hits'][$Provider]);
                $Call['Hits']['All'] += $Result['Meta']['Hits'][$Provider];

                if (in_array($Provider, $Call['Provider']))
                    $Call['Output']['Content'] = F::Merge($Call['Output']['Content'], $Result['SERP']);
            }
        }

        if (count($Call['Provider']) > 1)
            $Call['Hits']['Selected'] = $Call['Hits']['All'];
        else
            $Call['Hits']['Selected'] = $Call['Hits'][$Call['Provider'][0]];

        $Call = F::Hook('afterQuery', $Call);

        return $Call;
    });