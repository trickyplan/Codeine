<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Parser',
            'ID' => 'URL'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call['URL'] = $Call['Request']['Data']['URL'];
        $Call = F::Apply(null, 'Parse', $Call);
        return $Call;
    });

    setFn('Parse', function ($Call)
    {
        if ($Call['Schema'] = F::Run('Parser', 'Discovery', $Call))
        {
            F::Log('Schema is '.$Call['Schema'], LOG_INFO);
            $Schema = F::loadOptions('Parser/'.$Call['Schema']);
            $Call = F::Merge($Call, $Schema);

            $Result = F::Live($Call['Parser']['URL']['Backend'],
                [
                    'Where' =>
                        [
                            'ID' => $Call['URL']
                        ]
                ]);

            $Result = array_pop($Result);

            $Call = F::Run('Parser', 'Do', $Call, ['Markup' => $Result]);
            $Slices = explode('.', $Call['Schema']);
            $Call['Entity'] = array_pop($Slices);
            $Call['Data']['Source'] = $Call['URL'];

            $Call['Data'] = F::Run('Entity', 'Create', $Call, ['One' => true]);
            if (isset($Call['Data']['ID']))
                $Call = F::Run('System.Interface.HTTP', 'Redirect', $Call,
                    [
                        'Location' => '/control/'.$Call['Entity'].'/Show/'.$Call['Data']['ID']
                    ]);
        }
        else
            $Call['Data'] = null;

        $Call['Output']['Content'][] =
            [
                'Type' => 'Block',
                'Value' => j($Call['Data'])
            ];



        return $Call;
    });