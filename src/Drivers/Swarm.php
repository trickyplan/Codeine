<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        // Получить медок
        $Call['Honey'] = F::Run ('IO', 'Read',
        [
            'Storage' => 'Swarm',
            'Scope'   => $Call['HTTP']['Host'],
            'Where'   =>
            [
                'ID' => $Call['Swarm']['ID']
            ]
        ]);

        if (empty($Call['Honey'])) // Если горшочек пуст
        {
            F::Log('Bootstraping swarm', LOG_INFO);
            $Call['Honey'][sha1($Call['HTTP']['Host'])] = $Call['HTTP']['Host'];
            $Call['Honey'][sha1($Call['Swarm']['Bootstrap'])] = $Call['Swarm']['Bootstrap'];
        } // Заполняем его стартовой точкой
        else
        {
            F::Log('Loading swarm', LOG_INFO);
            $Call['Honey'] = $Call['Honey'][0];
        } // Грузим

        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        // Обходим рой
        F::Log('Traversing swarm', LOG_INFO);
        F::Log($Call['Honey'], LOG_INFO);

        foreach ($Call['Honey'] as $Honey)
        {
            // В рое есть и мы
            if ($Honey == $Call['HTTP']['Host'])
                continue;

            // Если мы стучим в того, кто стучал к нам, будет плохо
            if (isset($Call['Request']['Honey']) && ($Call['Request']['Honey'] == $Honey))
                continue;

            F::Log('Traversing '.$Honey, LOG_INFO);
            $Response = F::Run('IO', 'Write',
            [
                'Storage'   => 'Web',
                'Data'      =>
                [
                    'Honey' => $Call['HTTP']['Host']
                ],
                'Where'     =>
                [
                    'ID' => $Call['HTTP']['Proto'].$Honey.'/swarm'
                ]
            ]);

            F::Log($Response, LOG_INFO);

            if (empty($Response))
                ;
            else
                foreach ($Response as $cResponse)
                    $Call['Honey'] = F::Merge($Call['Honey'], json_decode($cResponse, true)['Honey']);
        }

        $Call['Output']['Content']['Honey'] = $Call['Honey'];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        // Обходим рой
            F::Log('Traversing swarm', LOG_INFO);
            F::Log($Call['Honey'], LOG_INFO);

            foreach ($Call['Honey'] as $Honey)
            {
                // В рое есть и мы
                if ($Honey == $Call['HTTP']['Host'])
                    continue;

                // Если мы стучим в того, кто стучал к нам, будет плохо
                if (isset($Call['Request']['Honey']) && ($Call['Request']['Honey'] == $Honey))
                    continue;

                F::Log('Traversing '.$Honey, LOG_INFO);

                $Response = F::Run('IO', 'Read',
                [
                    'Storage'   => 'Web',
                    'Where'     =>
                    [
                        'ID' => $Call['HTTP']['Proto'].$Honey.'/swarm'
                    ]
                ]);

                F::Log($Response, LOG_INFO);

                if (empty($Response))
                    ;
                else
                    foreach ($Response as $cResponse)
                        $Call['Honey'] = F::Merge($Call['Honey'], json_decode($cResponse, true)['Honey']);
            }

            // Если нам принесли медок
            if (isset($Call['Request']['Honey']) && !isset($Call['Honey'][sha1($Call['Request']['Honey'])]))
            {
                $Call['Honey'][sha1($Call['Request']['Honey'])] = $Call['Request']['Honey'];

                // Медок в горшок!
                F::Run ('IO', 'Write',
                [
                    'Storage' => 'Swarm',
                    'Scope'   => $Call['HTTP']['Host'],
                    'Where' =>
                    [
                        'ID' => $Call['Swarm']['ID']
                    ],
                    'Data' => $Call['Honey']
                ]);
            }

            $Call['Output']['Content']['Honey'] = $Call['Honey'];

        return $Call;
    });
