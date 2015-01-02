<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $UserBalances = F::Run('Entity', 'Read', $Call,
        [
            'Entity' => 'User',
            'Fields' => ['Balance']
        ]);


        $UserBalances = F::Extract($UserBalances, 'Balance')['Balance'];
        $Call['Balance'] =
        [
            'Users' => array_sum($UserBalances)
        ];
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