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

        foreach ($Call['Providers'] as $ProviderName => $ProviderCall)
        {
            $Results = F::Live($ProviderCall, $Call);

            foreach ($Results as $Result)
            {
                $Result['Provider'] = $ProviderName;

                $Call['Output']['Content'][] =
                    [
                        'Type'  => 'Template',
                        'Scope' => 'Search',
                        'ID'    => 'Result',
                        'Data'  => $Result
                    ];
            }
        }

        return $Call;
    });


