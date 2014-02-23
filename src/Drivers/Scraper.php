<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */
    include_once 'phpQuery.php';

    setFn('Do', function ($Call)
    {
        F::Log('Start URL: '.$Call['Start'], LOG_WARNING);

        if (isset($Call['URLList']))
            $Call['URLs'] = explode(PHP_EOL, file_get_contents($Call['URLList']));
        else
            $Call['URLs'] = [$Call['Start']];

        $Call = F::Run(null, 'Load Proxy List', $Call);

        $Call['Live'] = true;
        $Call['Processed'] = [];

        $Call['IX'] = 0;

        while($Call['Live'])
        {
            shuffle($Call['URLs']);

            $Call['URL'] = array_shift($Call['URLs']);
            F::Log('URL: '.$Call['URL'].' selected', LOG_WARNING);
            $Call['Host'] = parse_url($Call['URL'], PHP_URL_HOST);

            $Call = F::Run(null, 'Select Filename', $Call);

            if (file_exists($Call['Filename']))
            {
                F::Log($Call['URL'].' skipped', LOG_WARNING);
                $Call = F::Run(null, 'Read', $Call);
            }
            else
            {
                $Call =
                    F::Run(null, 'Write',
                        F::Run(null, 'Fetch',
                            F::Run(null, 'Select User Agent',
                                F::Run(null, 'Select Proxy', $Call)
                            )
                        ));

                sleep(rand(0,$Call['Pause']));
            }

            if (isset($Call['URLList']))
                ;
            else
                $Call = F::Run(null, 'Get Links', $Call);

            $Call['Processed'][] = $Call['URL'];

            $Call['URLs'] = array_unique($Call['URLs']);

            $ProcessedCount = count($Call['Processed']);
            $QueuedCount = count($Call['URLs']);

            F::Log($QueuedCount.' URLs queued', LOG_WARNING);
            F::Log($ProcessedCount.' URLs processed', LOG_WARNING);

            F::Log($ProcessedCount/($QueuedCount+$ProcessedCount).'%', LOG_WARNING);

            if(count($Call['URLs']) == 0)
                break;

            $Call['IX']++;
        }

        return $Call;
    });

    setFn('Select Filename', function ($Call)
    {
        $Root = '/var/cache/scraped/'.$Call['Host'];
        $Call['Filename'] = $Root.$Call['URL'];

        if (substr($Call['Filename'], strlen($Call['Filename'])-1, 1) == '/')
            $Call['Filename'] = substr($Call['Filename'], 0, strlen($Call['Filename'])-1).'.html';
        elseif (substr($Call['Filename'], strlen($Call['Filename'])-5) != '.html')
            $Call['Filename'] .= '.html';

        F::Log('Filename: '.$Call['Filename'].' selected', LOG_WARNING);

        return $Call;
    });

    setFn('Fetch', function ($Call)
    {
        F::Log($Call['URL'].' fetching', LOG_WARNING);
        $MT = microtime(true);

        $Result = F::Run('IO', 'Read', $Call, ['No Memo' => true, 'Storage' => 'Web', 'Where' => $Call['URL']]);
        $Call['Body'] = array_pop($Result);

        F::Log($Call['URL'].' fetched by '.round((microtime(true)-$MT)*1000).' ms', LOG_WARNING);

        return $Call;

    });

    setFn('Write', function ($Call)
    {
        $Decision = true;

        if (isset($Call['WhiteStore']) && !preg_match($Call['WhiteStore'], $Call['URL']))
            $Decision = false;

        if (isset($Call['BlackStore']) && preg_match($Call['BlackStore'], $Call['URL']))
            $Decision = false;

        if ($Decision)
        {
            $Subroot = dirname($Call['Filename']);

            if(!is_dir($Subroot))
                mkdir ($Subroot, 0777, true);

            file_put_contents($Call['Filename'], $Call['Body']);
            F::Log($Call['Filename'].' writed', LOG_WARNING);
        }

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        $Call['Body'] = file_get_contents($Call['Filename']);
        return $Call;
    });

    setFn('Get Links', function ($Call)
    {
        phpQuery::newDocumentHTML($Call['Body']);

        phpQuery::each(pq('a'),function($Index, $Element) use (&$Call)
        {
            $URL = parse_url($Element->getAttribute('href'));

            if (isset($URL['host']) && 'http://'.$URL['host'] != $Call['Host'])
                $Decision = false;
            else
                $Decision = true;

            $URL = (isset($URL['path'])? $URL['path']: '').(isset($URL['query'])? '?'.$URL['query']: '');

            if (in_array($URL, $Call['Processed']) or in_array($URL, $Call['URLs']))
                $Decision = false;

            if (isset($Call['White']) && !preg_match($Call['White'], $URL))
                $Decision = false;

            if (isset($Call['Black']) && preg_match($Call['Black'], $URL))
                $Decision = false;

            if (substr($URL, 0, 1) != '/')
                $URL = '/'.$URL;

            if ($Decision)
                $Call['URLs'][] = $Call['Host'].'/'.$URL;

        });

        phpQuery::unloadDocuments();

        return $Call;
    });

    setFn('Select Proxy', function ($Call)
    {
        if (isset($Call['NoProxy']))
            ;
        else
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
        if (isset($Call['NoUA']))
            ;
        else
        {
            $Call['User Agent'] = $Call['User Agents'][array_rand($Call['User Agents'])];
            F::Log('UA: '.$Call['User Agent'].' selected', LOG_WARNING);
        }

        return $Call;
    });

    setFn('Load Proxy List', function ($Call)
    {
        if (isset($Call['NoProxy']))
            ;
        else
        {
            if (file_exists(Root.'/proxy_http_ip.txt'))
            {
                F::Log(Root.'/proxy_http_ip.txt detected ', LOG_WARNING);
                $List = file(Root.'/proxy_http_ip.txt');

                foreach ($List as $Line)
                    if (!empty($Line))
                        $Call['Proxies'][] = trim($Line);

                F::Log(count($Call['Proxies']).' proxies loaded', LOG_WARNING);
            }
        }

        return $Call;
    });