<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return $Call;
    });

    setFn('Currency', function ($Call)
    {
        $Currencies = F::loadOptions('Finance.Currency')['Currencies'];

        foreach ($Currencies as $From => $Pair)
            foreach ($Pair as $Currency => $Live)
            {
                $Call['Output']['Content'][] =
                    [
                        'Type'  => 'Block',
                        'Class' => 'list-group-item',
                        'Value' => '1 '.$From.' = '.F::Live($Live['Rate'], $Call).' '.$Currency
                    ];
            }

        return $Call;
    });