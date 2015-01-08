<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        F::Log($Call['Host'].' loading', LOG_WARNING);

        $Call['URLs'] = [$Call['Start']];

        if (isset($Call['Proxy']) && $Call['Proxy'])
            $Call = F::Run(null, 'Load Proxy List', $Call);

        $Call['Live'] = true;
        $Call['Processed'] = [];
        $Call['IX'] = 0;

        while($Call['Live'])
        {
            $Call['URL'] = array_shift($Call['URLs']);

            F::Log('URL: '.$Call['Host'].$Call['URL'].' selected', LOG_WARNING);

                $Call = F::Run(null, 'Fetch', $Call);

            $URLs = F::Run(null, 'Get Links', $Call);

            $Call['Processed'][] = $Call['URL'];

            foreach ($URLs as $cURL)
                $Links[$Call['URL']][$cURL] = $cURL;

            $Call['URLs'] = array_merge($Call['URLs'], $URLs);
            $Call['URLs'] = array_unique($Call['URLs']);

            F::Log(count($Call['URLs']).' URLs queued', LOG_WARNING);
            F::Log(count($Call['Processed']).' URLs processed', LOG_WARNING);

            if(count($Call['URLs']) == 0 or count($Links) > 200)
                break;

            F::Run('IO', 'Write',
                [
                    'Storage' => 'Scraped',
                    'Where' =>
                    [
                        'ID' => strtr($Call['URL'], '/', '_')
                    ],
                    'Data' => $Call['Body']
                ]);

            $Call['IX']++;
        }
        return $Call;
    });

    setFn('Fetch', function ($Call)
    {
        F::Log('Fetching '.$Call['URL'], LOG_WARNING);

            $Call = F::Hook('beforeFetch', $Call);
                $Result = F::Run('IO', 'Read',
                    [
                        'Storage' => 'Web',
                        'Where' => $Call['Host'].$Call['URL']
                    ]);

                $Call['Body'] = array_pop($Result);
            $Call = F::Hook('afterFetch', $Call);

        F::Log('Fetched '.strlen($Call['Body']), LOG_WARNING);

        return $Call;

    });