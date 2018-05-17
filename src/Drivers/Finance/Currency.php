<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Rate.List', function ($Call)
    {
        $Currencies = [];
        
        foreach ($Call['Currency']['Available'] as $FirstCurrency => $SecondCurrencies)
            foreach ($SecondCurrencies as $SecondCurrency => $Run)
                $Currencies[$FirstCurrency][$SecondCurrency] = F::Live($Run['Rate']);

        return $Currencies;
    });

    setFn('Rate.Get', function ($Call)
    {
        return F::Live($Call['Currencies'])[$Call['From']][$Call['To']]['Rate'];
    });

    setFn('Rate.Convert', function ($Call)
    {
        return $Call['Value']*F::Live($Call['Currencies'][$Call['From']][$Call['To']]['Rate']);
    });