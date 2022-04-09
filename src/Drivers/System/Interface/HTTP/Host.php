<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeHostDetermine', $Call);

            $Call = F::Apply(null, 'Determine.Proto', $Call);
            $Call = F::Apply(null, 'Determine.Port', $Call);
            $Call = F::Apply(null, 'Determine.Host', $Call);
            $Call = F::Apply(null, 'Generate.FQDN', $Call);

        return F::Hook('afterHostDetermine', $Call);
    });

    setFn('Determine.Proto', function ($Call) {
        $Call['HTTP']['Secure'] = false;

        $Keys = ['HTTPS', 'HTTP_X_FORWARDED_PROTO', 'HTTP_X_HTTPS'];

        foreach ($Keys as $Key) {
            if (F::Dot($_SERVER, $Key)) {
                $Call['HTTP']['Secure'] = true;
                F::Log('HTTPS is *On* (' . $Key . ')', LOG_INFO);
                break;
            }
        }

        $Call['HTTP']['Proto'] = $Call['HTTP']['Secure'] ? 'https://' : 'http://';

        return $Call;
    });

    setFn('Determine.Port', function ($Call)
    {
        $Port = 0;

        if (F::Dot($Call, 'HTTP.Secure') && isset($_SERVER['CODEINE_HTTPS_PORT']))
        {
            $Port = $_SERVER['CODEINE_HTTPS_PORT'];
            F::Log('Port: *'.$Port.'* (via CODEINE_HTTPS_PORT)', LOG_INFO);
        }
        elseif (isset($_SERVER['CODEINE_HTTP_PORT']))
        {
            $Port = $_SERVER['CODEINE_HTTP_PORT'];
            F::Log('Port: *'.$Port.'* (via CODEINE_HTTP_PORT)', LOG_INFO);
        }
        elseif (str_contains($_SERVER['HTTP_HOST'], ':'))
        {
            list(,$Port) = explode(':', $_SERVER['HTTP_HOST']);
            F::Log('Port: *'.$Port.'* (via HTTP_HOST)', LOG_INFO);
        }
        elseif (isset($_SERVER['SERVER_PORT']))
        {
            $Port = $_SERVER['SERVER_PORT'];
            F::Log('Port: *'.$Port.'* (via SERVER_PORT)', LOG_INFO);
        }

        $Call['HTTP']['Port'] = $Port;
        return $Call;
    });

    setFn('Determine.Host', function ($Call)
    {
        $Host = '';

        if (isset($_SERVER['CODEINE_HTTP_HOST']))
        {
            $Host = $_SERVER['CODEINE_HTTP_HOST'];
            F::Log('Host: *'.$Host.'* (via CODEINE_HTTP_HOST)', LOG_INFO);
        }
        elseif (isset($_SERVER['HTTP_HOST']))
        {
            if (str_contains($_SERVER['HTTP_HOST'], ':'))
                list($Host,) = explode(':', $_SERVER['HTTP_HOST']);
            else
                $Host = $_SERVER['HTTP_HOST'];

            F::Log('Host: *'.$Host.'* (via HTTP_HOST)', LOG_INFO);
        }

        $Call['HTTP']['Host'] = $Host;

        return $Call;
    });

    setFn('Generate.FQDN', function ($Call) {
        $Call['HTTP']['FQDN'] = $Call['HTTP']['Proto'] . $Call['HTTP']['Host'];
        if ($Call['HTTP']['Proto'] === 'http' && $Call['HTTP']['Port'] != 80) {
            $Call['HTTP']['FQDN'] .= ':' . $Call['HTTP']['Port'];
        }

        if ($Call['HTTP']['Proto'] === 'https' && $Call['HTTP']['Port'] != 443) {
            $Call['HTTP']['FQDN'] .= ':' . $Call['HTTP']['Port'];
        }
        F::Log('FQDN: *'.$Call['HTTP']['FQDN'].'*', LOG_INFO);

        return $Call;
    });