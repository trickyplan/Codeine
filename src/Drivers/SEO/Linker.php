<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    include_once 'phpQuery/phpQuery.php';

    setFn('Do', function ($Call)
    {
        F::Log($Call['Host'].' loading', LOG_WARNING);

        $Call['URLs'] = [$Call['Start']];

        // $Call = F::Run(null, 'Load Proxy List', $Call);

        $Call['Live'] = true;
        $Call['Processed'] = [];
        $Links = [];

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

            $Call['IX']++;
        }

        $Graphs = 'graph Links{';

        foreach ($Links as $Donor => $Acceptors)
            foreach ($Acceptors as $Acceptor)
                $Graphs.= '"'.$Donor.'"--"'.$Acceptor.'";'.PHP_EOL;

        $Graphs .= '}';

        echo $Graphs;

        return $Call;
    });

    setFn('Fetch', function ($Call)
    {
        F::Log('Fetching '.$Call['URL'], LOG_WARNING);

        $Result = F::Run('IO', 'Read', ['Storage' => 'Web', 'Where' => $Call['Host'].$Call['URL']]);
        $Call['Body'] = array_pop($Result);
        F::Log('Fetched '.strlen($Call['Body']), LOG_WARNING);

        return $Call;

    });

    setFn('Get Links', function ($Call)
    {
        phpQuery::newDocumentHTML($Call['Body']);

        $URLs = [];

        phpQuery::each(pq('a'),function($Index, $Element) use (&$Call, &$URLs)
        {
            $URL = parse_url($Element->getAttribute('href'));

            if (!isset($URL['scheme']) || $URL['scheme'] == 'http')
            {
                if (isset($URL['host']) && 'http://'.$URL['host'] != $Call['Host'])
                    $Decision = false;
                else
                    $Decision = true;

                $URL = (isset($URL['path'])? $URL['path']: '');

                if (in_array($URL, $Call['Processed']))
                    $Decision = false;

                if (isset($Call['White']) && !preg_match($Call['White'], $URL))
                    $Decision = false;

                if (isset($Call['Black']) && preg_match($Call['Black'], $URL))
                    $Decision = false;

                if (substr($URL, 0, 1) != '/')
                    $URL = '/'.$URL;

                if ($Decision)
                    $URLs[] = $URL;
            }
        });

        phpQuery::unloadDocuments();

        return $URLs;
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