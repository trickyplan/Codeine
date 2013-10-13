<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Scope']) || $Call['Scope'] != 'Control')
        {
            $Call = F::Hook('beforeBillingDo', $Call);

                $Call = F::Apply(null, $_SERVER['REQUEST_METHOD'], $Call);

            $Call = F::Hook('afterBillingDo', $Call);
        }

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeBillingGet', $Call);

        $Call['Balance']['New'] = $Call['Session']['User']['Balance']-$Call['Cost'];

        if ($Call['Balance']['New'] >= $Call['Balance']['Minimal'])
            $Call['Output']['Message'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Entity/Billing',
                    'ID' => 'Warning'
                ];
        else
        {
            $Call['Failure'] = true;
            $Call['Output']['Message'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Entity/Billing',
                    'ID' => 'NotEnough'
                ];
        }

        $Call = F::Hook('afterBillingGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeBillingPost', $Call);

        $Call['Balance']['New'] = $Call['Session']['User']['Balance']-$Call['Cost'];

        if ($Call['Balance']['New'] >= $Call['Balance']['Minimal'])
        {
            F::Run('Entity', 'Update',
                [
                    'Entity' => 'User',
                    'Where' => $Call['Session']['User']['ID'],
                    'One'  => true,
                    'Data' =>
                    [
                        'Balance' => $Call['Balance']['New']
                    ]
                ]);

            $Call['Output']['Message'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Entity/Billing',
                    'ID' => 'Paid'
                ];
        }
        else
            $Call = F::Apply('Error.402', 'Page', $Call);

        $Call = F::Hook('afterBillingPost', $Call);

        return $Call;
    });