<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.x
     */
    include_once 'phpQuery/phpQuery.php';

    setFn('Do', function ($Call)
    {
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Parser',
            'ID' => 'Spider'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        foreach ($Call['Spider']['Tasks'] as $Name => $Task)
        {
            $Result = F::Live($Task['Backend'],
                [
                    'Where' =>
                        [
                            'ID' => $Task['URL']
                        ]
                ]);

            $Data = [];
            $Result = array_pop($Result);

            phpQuery::newDocumentHTML($Result);

            phpQuery::each(pq($Task['Selector']),function($Index, $Element) use (&$Data)
            {
                $Data[$Index] = pq($Element)->attr('href');
            });

            foreach ($Data as $Row)
                F::Run('Code.Run.Delayed', 'Run',
                    [
                        'Run' =>
                            [
                                'Service' => 'Parser.URL',
                                'Method' => 'Parse',
                                'Call' =>
                                [
                                    'URL' => $Task['Host'].$Row
                                ]
                            ]
                    ]);
        }
        return $Call;
    });