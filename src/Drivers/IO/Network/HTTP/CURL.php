<?php

    /* Codeine
    * @author BreathLess
    * @description
    * @package Codeine
    * @version 7.1
    */

    setFn('Open', function ($Call)
    {
        return true;
    });

    setFn('Read', function ($Call)
    {
        $Return = null;

        if (is_array($Call['Where']['ID']))
        {
            $Call['Link'] = curl_multi_init();

            $Links = [];

            foreach ($Call['Where']['ID'] as $cID)
            {
                $Links[$cID] = curl_init($cID);

                curl_setopt_array($Links[$cID],
                  [
                       CURLOPT_HEADER           => $Call['Return Header'],
                       CURLOPT_RETURNTRANSFER   => true,
                       CURLOPT_COOKIEJAR        => $Call['Cookie File'],
                       CURLOPT_FOLLOWLOCATION   => $Call['Follow'],
                       CURLOPT_CONNECTTIMEOUT   => $Call['Connect Timeout'],
                       CURLOPT_PROXY            => $Call['Proxy']['Host'],
                       CURLOPT_PROXYPORT        => $Call['Proxy']['Port'],
                       CURLOPT_USERAGENT        => $Call['User Agent'],
                       CURLOPT_FAILONERROR      => true
                  ]);

                if (isset($Call['Proxy']['Auth']))
                    curl_setopt($Links[$cID], CURLOPT_PROXYUSERPWD, $Call['Proxy']['Auth']);

                curl_multi_add_handle($Call['Link'], $Links[$cID]);
            }

            $Running = null;

            do
                curl_multi_exec($Call['Link'], $Running);
            while ($Running > 0);

            foreach ($Links as $ID => $Link)
            {
                $Return[$ID] = curl_multi_getcontent($Link);

                if ($Call['Return Header'] && isset($Call['Only Header']))
                {
                    $Size = curl_getinfo($Link, CURLINFO_HEADER_SIZE);
                    $Return[$ID] = substr($Return[$ID], 0, $Size);
                }

                if (curl_errno($Link))
                    F::Log('CURL error: '.curl_error($Link).'*'.$ID.'*', LOG_ERR);
                else
                    F::Log('CURL fetched *'.$ID.'*', LOG_INFO);

                curl_multi_remove_handle($Call['Link'], $Link);
            }

            curl_multi_close($Call['Link']);
        }
        else
        {
            $Call['Link'] = curl_init($Call['Where']['ID']);

            curl_setopt_array($Call['Link'],
                [
                   CURLOPT_HEADER           => $Call['Return Header'],
                   CURLOPT_RETURNTRANSFER   => true,
                   CURLOPT_COOKIEJAR        => $Call['Cookie File'],
                   CURLOPT_FOLLOWLOCATION   => $Call['Follow'],
                   CURLOPT_CONNECTTIMEOUT   => $Call['Connect Timeout'],
                   CURLOPT_PROXY            => $Call['Proxy']['Host'],
                   CURLOPT_PROXYPORT        => $Call['Proxy']['Port'],
                   CURLOPT_USERAGENT        => $Call['User Agent'],
                   CURLOPT_FAILONERROR      => true
                ]);

            if (isset($Call['Proxy']['Auth']))
                curl_setopt($Call['Link'], CURLOPT_PROXYUSERPWD, $Call['Proxy']['Auth']);

            $Return = [curl_exec($Call['Link'])];

            if ($Call['Return Header'] && isset($Call['Only Header']))
            {
                $Size = curl_getinfo($Call['Link'], CURLINFO_HEADER_SIZE);
                $Return[0] = substr($Return[0], 0, $Size);
            }

            if (curl_errno($Call['Link']))
                F::Log(curl_error($Call['Link']).' *'.$Call['Where']['ID'].'*', LOG_ERR);
            else
                F::Log('Fetched '.$Call['Where']['ID'], LOG_INFO);

            curl_close($Call['Link']);
        }

        return $Return;
    });

    setFn('Write', function ($Call)
    {
        $Call['Link'] = curl_init($Call['Where']['ID']);

        $Headers = isset($Call['HTTP']['Headers'])? $Call['HTTP']['Headers']: [];
        // TODO HTTP DELETE

        curl_setopt_array($Call['Link'],
            [
                CURLOPT_HEADER           => $Call['Return Header'],
                CURLOPT_RETURNTRANSFER   => true,
                CURLOPT_COOKIEJAR        => $Call['Cookie File'],
                CURLOPT_FOLLOWLOCATION   => $Call['Follow'],
                CURLOPT_CONNECTTIMEOUT   => $Call['Connect Timeout'],
                CURLOPT_PROXY            => $Call['Proxy']['Host'],
                CURLOPT_PROXYPORT        => $Call['Proxy']['Port'],
                CURLOPT_USERAGENT        => $Call['User Agent'],
                CURLOPT_FAILONERROR      => true,
                CURLOPT_POST             => true,
                CURLOPT_COOKIEJAR        => $Call['Cookie File'],
                CURLOPT_HTTPHEADER       => $Headers,
                CURLOPT_USERPWD          => isset($Call['User'])?
                    $Call['User'].':'.$Call['Password']: null, // FIXME
                CURLOPT_HTTPAUTH         => CURLAUTH_BASIC,
                CURLOPT_POSTFIELDS       => http_build_query($Call['Data'])
            ]);

        $Result =  [curl_exec($Call['Link'])];

        if (curl_errno($Call['Link']))
            F::Log('CURL error: '.curl_error($Call['Link']).'*'.$Call['Where']['ID'].'*', LOG_ERR);
        else
            F::Log('CURL fetched *'.$Call['Where']['ID'].'*', LOG_INFO);

        curl_close ($Call['Link']);

        return $Result;
    });

    setFn ('Close', function ($Call)
    {
        curl_close ($Call['Link']);
        return true;
    });

    setFn('Execute', function ($Call)
    {
        return true;
    });

    setFn('Version', function ($Call)
    {
        $Call['Link'] = curl_init($Call['Where']['ID']);

        curl_setopt_array($Call['Link'],
                [
                    CURLOPT_HEADER => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_COOKIEJAR => $Call['CookieFile'],
                    CURLOPT_FILETIME => true,
                    CURLOPT_NOBODY => true,
                    CURLOPT_FOLLOWLOCATION => $Call['Follow'],
                    CURLOPT_CONNECTTIMEOUT => $Call['Timeout']
                ]);

        return curl_getinfo($Call['Link'])['filetime'];
    });

    setFn('Size', function ($Call)
    {
        return '∞';
    });