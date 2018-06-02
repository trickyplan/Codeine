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
                $Currencies[$FirstCurrency][$SecondCurrency] = F::Live($Run['Rate'], $Call);

        foreach ($Currencies as $FirstCurrency => $SecondCurrencies)
            foreach ($SecondCurrencies as $SecondCurrency => $Rate)
                $Currencies[$SecondCurrency][$FirstCurrency] = 1/$Rate;
            
        return $Currencies;
    });

    setFn('Rate.Get', function ($Call)
    {
        $Call['Currencies'] = F::Run(null, 'Rate.List', $Call);
        return F::Live($Call['Currencies'])[$Call['From']][$Call['To']]['Rate'];
    });

    setFn('Rate.Convert', function ($Call)
    {
        $Call['Currencies'] = F::Run(null, 'Rate.List', $Call);
        
        return $Call['Value']*F::Live($Call['Currencies'][$Call['From']][$Call['To']]);
    });