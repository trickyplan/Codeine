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
        $Call = F::Run(null, 'Bootstrap', $Call);
        $Call = F::Run(null, 'Load Proxy List', $Call);

        $Call['Live'] = true;
        $Call['Processed'] = [];
        $Call['IX'] = 1;

        while($Call['Live'])
        {
            if (isset($Call['Shuffle']))
                shuffle($Call['URLs']);

            $Call['URL'] = array_shift($Call['URLs']);
            $Call['Host'] = parse_url($Call['URL'], PHP_URL_HOST);

            F::Log('URL: '.$Call['URL'].' selected', LOG_WARNING);

            $Call['Filename'] = F::Run(null, 'Select Filename', $Call);
            F::Log('Filename: '.$Call['Filename'].' selected', LOG_INFO);

            $Call = F::Hook('beforeFetch', $Call);

                if (file_exists($Call['Filename']))
                    $Call = F::Run(null, 'Read', $Call);
                else
                {
                    $Call = F::Run(null, 'Fetch',$Call);
                    $Call = F::Run(null, 'Write', $Call);

                    if (isset($Call['Pause']))
                        sleep(rand(0,$Call['Pause']));
                }

            $Call = F::Hook('afterFetch', $Call);

            if (isset($Call['Spider']['Enabled']))
                $Call = F::Run(null, 'Spider', $Call);

            $Call['Processed'][] = $Call['URL'];

            $QueuedCount = count($Call['URLs']);
            F::Log(round(($Call['IX']/($QueuedCount+$Call['IX']))*100, 2).'% '.$Call['IX'].'/'.$QueuedCount, LOG_WARNING);

            $Call['Live'] = $QueuedCount > 0;
            $Call['IX']++;
        }

        return $Call;
    });

    setFn('Bootstrap', function ($Call)
    {
        if (isset($Call['Mode']))
        {
            F::Log('Scraper mode: '.$Call['Mode']);

            switch ($Call['Mode'])
            {
                case 'Start':
                    F::Log('Bootstrap URL: '.$Call['Bootstrap'], LOG_WARNING);
                    $Call['URLs'] = [$Call['Host'].$Call['Bootstrap']];
                break;

                case 'List':
                    $Call['URLs'] = explode(PHP_EOL, file_get_contents($Call['Bootstrap']));
                break;

                case 'SitemapIndex':
                    $SitemapIndex = simplexml_load_string(file_get_contents($Call['Bootstrap']));

                    foreach ($SitemapIndex->sitemap as $Sitemap)
                    {
                        $Sitemap = (string) $Sitemap->loc;
                        $Sitemap = simplexml_load_string(file_get_contents($Sitemap));

                        foreach ($Sitemap as $URL)
                            $Call['URLs'][] = $URL->loc;

                    }
                break;

                case 'Sitemap':
                    $Sitemap = simplexml_load_string(file_get_contents($Call['Bootstrap']));
                    foreach ($Sitemap as $URL)
                        $Call['URLs'][] = $URL->loc;
                break;
            };

            F::Log(count($Call['URLs']).' bootstrapped', LOG_WARNING);
        }
        else
        {
            F::Log('Scraper mode not set', LOG_CRIT);
            die();
        }

        return $Call;
    });

    setFn('Select Filename', function ($Call)
    {
        if (substr($Call['URL'], 0, 1) == '/')
            $Call['Filename'] = $Call['URL'];
        else
        {
            $Root = $Call['Scraped'].$Call['Host'];
            $Query = parse_url($Call['URL'], PHP_URL_QUERY);

            if (strlen($Query) > 128)
                $Query = sha1($Query);

            $Call['Filename'] = $Root.parse_url($Call['URL'], PHP_URL_PATH).$Query;

            if (substr($Call['Filename'], strlen($Call['Filename'])-1, 1) == '/')
                $Call['Filename'] = substr($Call['Filename'], 0, strlen($Call['Filename'])-1).'.html';
            elseif (substr($Call['Filename'], strlen($Call['Filename'])-5) != '.html')
                $Call['Filename'] .= '.html';
        }

        return $Call['Filename'];
    });

    setFn('Fetch', function ($Call)
    {
            $MT = microtime(true);
            F::Log($Call['URL'].' fetching', LOG_INFO);

            if(substr($Call['URL'], 0,1) == '/')
                $Call['Body'] = file_get_contents($Call['URL']);
            else
            {
                $Result = F::Run('IO', 'Read', $Call,
                    [
                        'No Memo' => true,
                        'Storage' => 'Web',
                        'Random Proxy' => true,
                        'Random User Agent' => true,
                        'Connect Timeout' => 5,
                        'Where' =>
                            [
                                'ID' => $Call['URL']
                            ]
                    ]);
                $Call['Body'] = array_pop($Result);
            }

            F::Log($Call['URL'].' fetched by '.round((microtime(true)-$MT)*1000).' ms', LOG_WARNING);

        return $Call;

    });

    setFn('Write', function ($Call)
    {
        $Call = F::Hook('beforeWrite', $Call);

            $Decision = true;

            if (isset($Call['Store']['White']) && !preg_match('@'.$Call['Store']['White'].'@', $Call['URL']))
                $Decision = false;

            if (isset($Call['Store']['Black']) && preg_match('@'.$Call['Store']['Black'].'@', $Call['URL']))
                $Decision = false;

            if ($Decision)
            {
                $Subroot = dirname($Call['Filename']);

                if(!is_dir($Subroot))
                    mkdir ($Subroot, 0777, true);

                file_put_contents($Call['Filename'], $Call['Body']);
                F::Log($Call['Filename'].' writed', LOG_WARNING);
            }

        $Call = F::Hook('afterWrite', $Call);

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        $Call['Body'] = file_get_contents($Call['Filename']);
        return $Call;
    });

    setFn('Spider', function ($Call)
    {
        phpQuery::newDocumentHTML($Call['Body']);

        $Call['NL'] = 0;

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
            elseif (isset($Call['Spider']['White']) && !preg_match('@'.$Call['Spider']['White'].'@', $URL))
                $Decision = false;
            elseif (isset($Call['Spider']['Black']) && preg_match('@'.$Call['Spider']['Black'].'@', $URL))
                $Decision = false;
            elseif(file_exists(F::Run(null, 'Select Filename', $Call, ['URL' => $URL])))
                $Decision = false;

            if (substr($URL, 0, 1) != '/')
                $URL = '/'.$URL;

            if ($Decision)
            {
                $Call['URLs'][] = 'http://'.$Call['Host'].$URL;
                $Call['NL']++;
            }

        });

        phpQuery::unloadDocuments();

        F::Log('New links founded '.$Call['NL'], LOG_WARNING);

        return $Call;
    });

    setFn('Load Proxy List', function ($Call)
    {
        if (file_exists($Call['ProxyList']))
        {
            F::Log($Call['ProxyList'].' detected ', LOG_WARNING);
            $List = file($Call['ProxyList']);

            foreach ($List as $Line)
                if (!empty($Line))
                    $Call['Proxies'][] = trim($Line);

            F::Log(count($Call['Proxies']).' proxies loaded', LOG_WARNING);
        }

        return $Call;
    });