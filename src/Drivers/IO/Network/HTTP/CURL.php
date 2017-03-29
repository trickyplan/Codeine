<?php

    /* Codeine
    * @author bergstein@trickyplan.com
    * @description
    * @package Codeine
    * @version 7.1
    */

    setFn('Open', function ($Call)
    {
        return true;
    });

    setFn('Select User Agent', function ($Call)
    {
        if (isset($Call['CURL']['Random User Agent']))
        {
            $Call['CURL']['Agent'] = $Call['CURL']['Agents'][array_rand($Call['CURL']['Agents'])];
            F::Log('UA: '.$Call['CURL']['Agent'].' selected', LOG_INFO, 'Administrator');
        }
        return $Call;
    });

    setFn('Select Proxy', function ($Call)
    {
        if (isset($Call['CURL']['Random Proxy']) && isset($Call['CURL']['Proxies']))
        {
            $Call['CURL']['Proxies'] = F::Live($Call['CURL']['Proxies']);

            $Random = $Call['CURL']['Proxies'][array_rand($Call['CURL']['Proxies'])];

            if (strpos($Random, ':') === false)
            {
                $Call['CURL']['Proxy']['Host'] = $Random;
                $Call['CURL']['Proxy']['Port'] = 80;
            }
            else
                list($Call['CURL']['Proxy']['Host'], $Call['CURL']['Proxy']['Port']) = explode(':', $Random);
        }

        if (isset($Call['CURL']['Proxy']['Host']))
            F::Log('Proxy: '.$Call['CURL']['Proxy']['Host'].':'.$Call['CURL']['Proxy']['Port'].' selected', LOG_INFO, 'Administrator');

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        $Return = null;

        $Call = F::Run(null, 'Delay', $Call);
        $Call = F::Run(null, 'Select User Agent', $Call);
        $Call = F::Run(null, 'Select Proxy', $Call);

        if (is_array($Call['Where']['ID']))
        {
            $Call['Link'] = curl_multi_init();

            $Links = [];

            foreach ($Call['Where']['ID'] as $cID)
            {
                if (isset($Call['Data']))
                    $cID.= '?'.http_build_query($Call['Data']);

                $Links[$cID] = curl_init($cID);

                F::Log('CURL GET Request Headers: *'.j($Call['CURL']['Headers']).'*', LOG_INFO, 'Administrator');
                curl_setopt_array($Links[$cID],
                  [
                       CURLOPT_HEADER           => $Call['CURL']['Return Header'],
                       CURLOPT_RETURNTRANSFER   => true,
                       CURLOPT_COOKIEJAR        => $Call['CURL']['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
                       CURLOPT_COOKIE           => $Call['CURL']['Cookie'],
                       CURLOPT_FOLLOWLOCATION   => $Call['CURL']['Follow'],
                       CURLOPT_REFERER          => $Call['CURL']['Referer'],
                       CURLOPT_CONNECTTIMEOUT   => $Call['CURL']['Connect Timeout'],
                       CURLOPT_PROXY            => $Call['CURL']['Proxy']['Host'],
                       CURLOPT_PROXYPORT        => $Call['CURL']['Proxy']['Port'],
                       CURLOPT_USERAGENT        => $Call['CURL']['Agent'],
                       CURLINFO_HEADER_OUT      => true,
                       CURLOPT_HTTPHEADER       => $Call['CURL']['Headers'],
                       CURLOPT_FAILONERROR      => false
                  ]);

                if (isset($Call['CURL']['Proxy']['Auth']))
                    curl_setopt($Links[$cID], CURLOPT_PROXYUSERPWD, $Call['CURL']['Proxy']['Auth']);

                curl_multi_add_handle($Call['Link'], $Links[$cID]);
            }

            $Running = null;

            do
                curl_multi_exec($Call['Link'], $Running);
            while ($Running > 0);

            foreach ($Links as $ID => $Link)
            {
                $Return[$ID] = curl_multi_getcontent($Link);

                if ($Call['CURL']['Return Header'] && isset($Call['CURL']['Only Header']))
                {
                    $Size = curl_getinfo($Link, CURLINFO_HEADER_SIZE);
                    $Return[$ID] = substr($Return[$ID], 0, $Size);
                }

                if (curl_errno($Link))
                {
                    F::Log('CURL GET error: '.curl_error($Link).'*'.$ID.'*', LOG_WARNING, 'Administrator');
                    F::Log($Return, LOG_WARNING, 'Administrator');
                }
                else
                    F::Log('CURL GET fetched *'.$ID.'*', LOG_INFO, 'Administrator');
                

                curl_multi_remove_handle($Call['Link'], $Link);
            }

            curl_multi_close($Call['Link']);
        }
        else
        {
            if (isset($Call['Data']))
                $Call['Where']['ID'].= '?'.http_build_query($Call['Data']);

            $Call['Link'] = curl_init($Call['Where']['ID']);

            F::Log('CURL GET Request Headers: *'.j($Call['CURL']['Headers']).'*', LOG_INFO, 'Administrator');
            curl_setopt_array($Call['Link'],
                [
                    CURLOPT_HEADER           => $Call['CURL']['Return Header'],
                    CURLOPT_RETURNTRANSFER   => true,
                    CURLOPT_COOKIEJAR        => $Call['CURL']['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
                    CURLOPT_COOKIE           => $Call['CURL']['Cookie'],
                    CURLOPT_FOLLOWLOCATION   => $Call['CURL']['Follow'],
                    CURLOPT_REFERER          => $Call['CURL']['Referer'],
                    CURLOPT_CONNECTTIMEOUT   => $Call['CURL']['Connect Timeout'],
                    CURLOPT_PROXY            => $Call['CURL']['Proxy']['Host'],
                    CURLOPT_PROXYPORT        => $Call['CURL']['Proxy']['Port'],
                    CURLOPT_HTTPHEADER       => $Call['CURL']['Headers'],
                    CURLOPT_USERAGENT        => $Call['CURL']['Agent'],
                    CURLINFO_HEADER_OUT      => true,
                    CURLOPT_SSL_VERIFYPEER   => false,
                    CURLOPT_FAILONERROR      => false
                ]);

            if (isset($Call['CURL']['Proxy']['Auth']))
                curl_setopt($Call['Link'], CURLOPT_PROXYUSERPWD, $Call['CURL']['Proxy']['Auth']);

            $Return = [curl_exec($Call['Link'])];
            $Call = F::Apply(null, 'Info', $Call);
            
            if ($Call['CURL']['Return Header'])
            {
                $Size = curl_getinfo($Call['Link'], CURLINFO_HEADER_SIZE);
                $Headers = mb_substr($Return[0], 0, $Size);
                $Body = mb_substr($Return[0], $Size);

                $HTTPStatus = curl_getinfo($Call['Link'], CURLINFO_HTTP_CODE);
                $Return = [$Body, '_Status' => $HTTPStatus, '_0' => $Headers];
                F::Log('CURL GET Response Headers: '.j(explode("\r\n", $Headers)), LOG_INFO, 'Administrator');
            }
            
            
            if ($Call['CURL']['Return Header'] && isset($Call['CURL']['Only Header']))
            {
                $Size = curl_getinfo($Call['Link'], CURLINFO_HEADER_SIZE);
                $Return[0] = substr($Return[0], 0, $Size);
            }
            
            if (curl_errno($Call['Link']))
            {
                F::Log('CURL GET error: '.curl_error($Call['Link']).' *'.$Call['Where']['ID'].'*', LOG_WARNING, 'Administrator');
                F::Log($Return, LOG_WARNING, 'Administrator');
            }
            else
                F::Log('CURL GET fetched '.$Call['Where']['ID'], LOG_INFO, 'Administrator');

            curl_close($Call['Link']);
        }

        return $Return;
    });

    setFn('Write', function ($Call)
    {
        $Call['Link'] = curl_init($Call['Where']['ID']);
        $Call = F::Run(null, 'Select User Agent', $Call);

        $Post = is_string($Call['Data']) ? $Call['Data'] : http_build_query($Call['Data']);

        F::Log('CURL POST Request Headers: *'.j($Call['CURL']['Headers']).'*', LOG_INFO, 'Administrator');
        curl_setopt_array($Call['Link'],
            [
                CURLOPT_HEADER           => $Call['CURL']['Return Header'],
                CURLOPT_RETURNTRANSFER   => true,
                CURLOPT_COOKIEJAR        => $Call['CURL']['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
                CURLOPT_COOKIE           => $Call['CURL']['Cookie'],
                CURLOPT_FOLLOWLOCATION   => $Call['CURL']['Follow'],
                CURLOPT_REFERER          => $Call['CURL']['Referer'],
                CURLOPT_CONNECTTIMEOUT   => $Call['CURL']['Connect Timeout'],
                CURLOPT_TIMEOUT          => $Call['CURL']['Overall Timeout'],
                CURLOPT_PROXY            => $Call['CURL']['Proxy']['Host'],
                CURLOPT_PROXYPORT        => $Call['CURL']['Proxy']['Port'],
                CURLOPT_USERAGENT        => $Call['CURL']['Agent'],
                CURLOPT_HTTPHEADER       => $Call['CURL']['Headers'],
                CURLINFO_HEADER_OUT      => true,
                CURLOPT_FAILONERROR      => false,
                CURLOPT_POST             => true,
                CURLOPT_SSL_VERIFYPEER => false,
                // CURLOPT_USERPWD          => isset($Call['User'])? $Call['User'].':'.$Call['Password']: null, // FIXME
                CURLOPT_HTTPAUTH         => CURLAUTH_BASIC,
                CURLOPT_POSTFIELDS       => $Post
            ]);

        $Result = [curl_exec($Call['Link'])];

        $Call = F::Apply(null, 'Info', $Call);
        
        if ($Call['CURL']['Return Header'])
        {
            $Size = curl_getinfo($Call['Link'], CURLINFO_HEADER_SIZE);
            $Headers = mb_substr($Result[0], 0, $Size);
            $Body = mb_substr($Result[0], $Size);

            $HTTPStatus = curl_getinfo($Call['Link'], CURLINFO_HTTP_CODE);
            $Result = [$Body, '_Status' => $HTTPStatus, '_0' => $Headers];
            
            F::Log('CURL GET Response Headers: '.j(explode("\r\n", $Headers)), LOG_INFO, 'Administrator');
        }

        if ($Call['CURL']['Return Header'] && isset($Call['CURL']['Only Header']))
        {
            $Size = curl_getinfo($Call['Link'], CURLINFO_HEADER_SIZE);
            $Result[0] = substr($Result[0], 0, $Size);
        }
        
        if (curl_errno($Call['Link']))
        {
            F::Log('CURL POST error: '.curl_error($Call['Link']).' *'.$Call['Where']['ID'].'* '.PHP_EOL.$Post, LOG_ERR, 'Administrator');
            F::Log($Result, LOG_WARNING, 'Administrator');
        }
        else
            F::Log('CURL POST fetched *'.$Call['Where']['ID'].'* '.$Post, LOG_INFO, 'Administrator');

        curl_close ($Call['Link']);

        return $Result;
    });

    setFn ('Close', function ($Call)
    {
        if (is_resource($Call['Link']))
            return curl_close ($Call['Link']);
        else
            return null;
    });

    setFn('Execute', function ($Call)
    {
        return true;
    });

    setFn('Version', function ($Call)
    {
/*        $Call['Link'] = curl_init($Call['Where']['ID']);
        $Call = F::Run(null, 'Select User Agent', $Call);

        curl_setopt_array($Call['Link'],
                [
                    CURLOPT_HEADER => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_COOKIEJAR => $Call['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
                    CURLOPT_FILETIME => true,
                    CURLOPT_NOBODY => true,
                    CURLOPT_FOLLOWLOCATION => $Call['Follow'],
                    CURLOPT_CONNECTTIMEOUT => $Call['Connect Timeout']
                ]);

        return curl_getinfo($Call['Link'])['filetime'];*/

        return 1;
    });

    setFn('Exist', function ($Call)
    {
        return true;
        F::Start('IO Curl Exist '.$Call['Where']['ID']);
        $Call['Link'] = curl_init($Call['Where']['ID']);
        $Call = F::Run(null, 'Select User Agent', $Call);

        curl_setopt_array($Call['Link'],
                [
                    CURLOPT_HEADER => true,
                    CURLOPT_COOKIEJAR => $Call['CURL']['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
                    CURLOPT_NOBODY => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_REFERER          => $Call['CURL']['Referer'],
                    CURLOPT_FOLLOWLOCATION => $Call['CURL']['Follow'],
                    CURLOPT_CONNECTTIMEOUT => $Call['CURL']['Connect Timeout'],
                    CURLOPT_USERAGENT        => $Call['CURL']['Agent']
                ]);

        $Head = curl_exec($Call['Link']);
        F::Log('CURL HEAD fetched *'.$Call['Where']['ID'].'* '.$Head, LOG_INFO, 'Administrator');
        $Result = (curl_getinfo($Call['Link'])['http_code'] == 200);

        F::Stop('IO Curl Exist '.$Call['Where']['ID']);
        return $Result;
    });

    setFn('Size', function ($Call)
    {
        return '∞';
    });

    setFn('Delay', function ($Call)
    {
        if (isset($Call['Random Delay']))
            $Call['Delay'] = rand(0, $Call['Random Delay']);

        if (isset($Call['Delay']))
            usleep($Call['Delay']);

        return $Call;
    });
    
    setFn('Info', function ($Call)
    {
        $CURLInfo = curl_getinfo($Call['Link']);
        $CURLInfo['request_header'] = explode("\r\n", $CURLInfo['request_header']);
        foreach ($CURLInfo as $Key => $Value)
            F::Log($Key.' = '.j($Value), LOG_INFO, 'Administrator');
        
        return $Call;
    });