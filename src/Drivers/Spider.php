<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
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


            file_put_contents('/var/scrape/'.$Call['URL'], $Call['Body']);
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



    setFn('Select Proxy', function ($Call)
    {
        if ($Call['IX']%$Call['RPP'] == 0)
        {
            $Call['Proxy'] = [];

            list($Call['Proxy']['Host'], $Call['Proxy']['Port']) =
                explode(':', $Call['Proxies'][array_rand($Call['Proxies'])]);

            F::Log('Proxy: '.$Call['Proxy']['Host'].' selected', LOG_WARNING);
        }

        return $Call;
    });

    setFn('Select User Agent', function ($Call)
    {
        if ($Call['IX']%$Call['RPP'] == 0)
        {
            $Call['User Agent'] = $Call['User Agents'][array_rand($Call['User Agents'])];
            F::Log('UA: '.$Call['User Agent'].' selected', LOG_WARNING);
        }

        return $Call;
    });

    setFn('Load Proxy List', function ($Call)
    {
        if (file_exists(Root.'/proxy_http_ip.txt'))
        {
            F::Log(Root.'/proxy_http_ip.txt detected ', LOG_WARNING);
            $List = file(Root.'/proxy_http_ip.txt');

            foreach ($List as $Line)
                if (!empty($Line))
                    $Call['Proxies'][] = trim($Line);
        }

        F::Log(count($Call['Proxies']).' proxies loaded', LOG_WARNING);
        return $Call;
    });