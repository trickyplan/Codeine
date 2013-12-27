<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeDepositDo', $Call);

            $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true]);
            $Call = F::Run(null, $Call['HTTP']['Method'], $Call);

        $Call = F::Hook('afterDepositDo', $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDepositGet', $Call);

        $Call['Layouts'][] =
            [
                'Scope' => 'Entity.Balance',
                'ID'    => 'Form'
            ];

        $Call['Output']['Content'][] =
            [
                'Name'  => 'Payment[Value]',
                'Type'  => 'Form.Textfield',
                'Label' => $Call['Entity'].'.Deposit:Value',
                'Value' => round($Call['Data']['Balance']-$Call['Data']['Costs'],2),
                'Postfix'   => $Call['Project']['Currency']
            ];

        $Options = [];

        foreach ($Call['Deposit Methods'] as $Method)
            $Options[$Method] = '<l>Payment.'.$Method.':Title</l>';

        $Call['Output']['Content'][] =
            [
                'Name'      => 'Payment[Method]',
                'Type'      => 'Form.Radiogroup',
                'Keys as values' => true,
                'Label'     => $Call['Entity'].'.Deposit:Method',
                'Value'     => 0,
                'Options'   => $Options
            ];

        $Call = F::Hook('afterDepositGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeDepositPost', $Call);

        if (in_array($Call['Request']['Payment']['Method'], $Call['Deposit Methods']))
            $Call = F::Apply('Payment.'.$Call['Request']['Payment']['Method'], 'Do', $Call);

        $Call = F::Hook('afterDepositPost', $Call);

        return $Call;
    });

    setFn('Close', function ($Call)
    {
        $Call = F::Hook('beforeDepositPost', $Call);
            $Call = F::Apply('Payment.'.$Call['Request']['Payment']['Method'], 'Finish', $Call);
        $Call = F::Hook('afterDepositPost', $Call);
        return $Call;
    });