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

    setFn('Select User Agent', function ($Call)
    {
        if (isset($Call['Random User Agent']))
        {
            $Call['User Agent'] = $Call['User Agents'][array_rand($Call['User Agents'])];
            F::Log('UA: '.$Call['User Agent'].' selected', LOG_INFO, 'Administrator');
        }
        return $Call;
    });

    setFn('Select Proxy', function ($Call)
    {
        if (isset($Call['Random Proxy']) && isset($Call['Proxies']))
        {
            list($Call['Proxy']['Host'], $Call['Proxy']['Port']) =
                explode(':', $Call['Proxies'][array_rand($Call['Proxies'])]);
        }

        if (isset($Call['Proxy']['Host']))
            F::Log('Proxy: '.$Call['Proxy']['Host'].':'.$Call['Proxy']['Port'].' selected', LOG_INFO, 'Administrator');

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        $Return = null;

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

                curl_setopt_array($Links[$cID],
                  [
                       CURLOPT_HEADER           => $Call['Return Header'],
                       CURLOPT_RETURNTRANSFER   => true,
                       CURLOPT_COOKIEJAR        => $Call['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
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
                    F::Log('CURL error: '.curl_error($Link).'*'.$ID.'*', LOG_WARNING, 'Administrator');
                else
                    F::Log('CURL fetched *'.$ID.'*', LOG_INFO, 'Administrator');

                curl_multi_remove_handle($Call['Link'], $Link);
            }

            curl_multi_close($Call['Link']);
        }
        else
        {
            if (isset($Call['Data']))
                $Call['Where']['ID'].= '?'.http_build_query($Call['Data']);

            $Call['Link'] = curl_init($Call['Where']['ID']);

            curl_setopt_array($Call['Link'],
                [
                   CURLOPT_HEADER           => $Call['Return Header'],
                   CURLOPT_RETURNTRANSFER   => true,
                   CURLOPT_COOKIEJAR        => $Call['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
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
                F::Log('CURL error: '.curl_error($Call['Link']).' *'.$Call['Where']['ID'].'*', LOG_WARNING, 'Administrator');
            else
                F::Log('CURL fetched '.$Call['Where']['ID'], LOG_INFO, 'Administrator');

            curl_close($Call['Link']);
        }

        return $Return;
    });

    setFn('Write', function ($Call)
    {
        $Call['Link'] = curl_init($Call['Where']['ID']);
        $Call = F::Run(null, 'Select User Agent', $Call);

        $Headers = isset($Call['HTTP']['Headers'])? $Call['HTTP']['Headers']: [];
        // TODO HTTP DELETE

        curl_setopt_array($Call['Link'],
            [
                CURLOPT_HEADER           => $Call['Return Header'],
                CURLOPT_RETURNTRANSFER   => true,
                CURLOPT_COOKIEJAR        => $Call['Cookie Directory'].DS.parse_url($Call['Where']['ID'], PHP_URL_HOST),
                CURLOPT_FOLLOWLOCATION   => $Call['Follow'],
                CURLOPT_CONNECTTIMEOUT   => $Call['Connect Timeout'],
                CURLOPT_PROXY            => $Call['Proxy']['Host'],
                CURLOPT_PROXYPORT        => $Call['Proxy']['Port'],
                CURLOPT_USERAGENT        => $Call['User Agent'],
                CURLOPT_FAILONERROR      => true,
                CURLOPT_POST             => true,
                CURLOPT_HTTPHEADER       => $Headers,
                CURLOPT_USERPWD          => isset($Call['User'])?
                    $Call['User'].':'.$Call['Password']: null, // FIXME
                CURLOPT_HTTPAUTH         => CURLAUTH_BASIC,
                CURLOPT_POSTFIELDS       => is_string($Call['Data'])?
                                            $Call['Data']
                                            : http_build_query($Call['Data'])
            ]);

        $Result =  [curl_exec($Call['Link'])];

        if (curl_errno($Call['Link']))
            F::Log('CURL error: '.curl_error($Call['Link']).'*'.$Call['Where']['ID'].'*', LOG_ERR);
        else
            F::Log('CURL fetched *'.$Call['Where']['ID'].'*', LOG_INFO, 'Administrator');

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
        $Call['Link'] = curl_init($Call['Where']['ID']);
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

        return curl_getinfo($Call['Link'])['filetime'];
    });

    setFn('Size', function ($Call)
    {
        return '∞';
    });