<?php

    /* Codeine
     * @author BreathLess
     * @description Web Interface 
     * @package Codeine
     * @version 7.1
     */

    setFn ('Do', function ($Call)
    {
        $Call = F::Hook('beforeInterfaceRun', $Call);

        F::Log('Interface *Web* started', LOG_INFO);

        // HTTP Method determining
        $Call['HTTP']['Method'] =
            in_array($_SERVER['REQUEST_METHOD'], $Call['HTTP']['Methods']['Allowed'])?
            $_SERVER['REQUEST_METHOD']:
            $Call['HTTP']['Methods']['Default'];

        F::Log('Method: *'.$Call['HTTP']['Method'].'*', LOG_INFO);

        // Merge FILES to REQUEST.
        if (isset($_FILES['Data']))
            foreach ($_FILES['Data']['tmp_name'] as $IX => $Value)
                if (is_array($Value) && count($Value) > 0)
                    foreach ($Value as $K2 => $V2)
                    {
                        if (!empty($V2))
                            $_REQUEST['Data'][$IX][$K2] = $V2;
                    }
                else
                    $_REQUEST['Data'][$IX] = $Value;

        foreach ($_SERVER as &$Request)
            $Request = str_replace(chr(0), '', rawurldecode($Request));

        foreach ($_REQUEST as $Key => $Value)
            $Call['Request'][$Key] = str_replace(chr(0), '', $Value);

        if (empty($Call['Request']))
            ;
        else
            F::Log($Call['Request'], LOG_INFO);

        // Cookie reading
        $Call['HTTP']['Cookie'] = $_COOKIE;

        if (empty($Call['HTTP']['Cookie']))
            ;
        else
            F::Log($Call['HTTP']['Cookie'], LOG_INFO);

        if (isset($_SERVER['HTTP_REFERER']))
            $Call['HTTP']['Referer'] = $_SERVER['HTTP_REFERER'];

        // Query string reading

        // Merge slashes
        $_SERVER['REQUEST_URI'] = preg_replace('/^(\/+)/Ssu', '/', $_SERVER['REQUEST_URI']);

        $Call['HTTP']['URI'] = $_SERVER['REQUEST_URI'].(empty($Call['HTTP']['URL Query'])? '' : '');

        F::Log('URI: *'.$Call['HTTP']['URI'].'*', LOG_INFO);

        $Call['HTTP']['URL'] = parse_url($Call['HTTP']['URI'], PHP_URL_PATH);
        F::Log('URL: *'.$Call['HTTP']['URI'].'*', LOG_INFO);

        $Call['HTTP']['URL Query'] = parse_url($Call['HTTP']['URI'], PHP_URL_QUERY);

        empty($Call['HTTP']['URL Query'])?
            F::Log('Empty query string.', LOG_INFO):
            F::Log('Query string: *'.$Call['HTTP']['URL Query'].'*', LOG_INFO);

        $Call['Run'] = $Call['HTTP']['URI'];
        F::Log('Run String: '.$Call['Run'], LOG_INFO);

        $Call = F::Apply(null, 'Protocol', $Call);

        $Call['HTTP']['User Agent'] = F::Live($Call['HTTP']['User Agent'], $Call);
        $Call['HTTP']['IP'] = F::Live($Call['HTTP']['IP'], $Call);
        $Call['Locale'] = F::Live($Call['Locale'], $Call);

        $Call = F::Hook('beforeRequestRun', $Call);

        try
        {
            $Call = F::Apply($Call['Service'], $Call['Method'], $Call);
        }
        catch (Exception $e)
        {
            F::Log($e->getMessage(), LOG_CRIT, 'Developer');

            switch ($_SERVER['Environment'])
            {
                case 'Development':
                    d(__FILE__, __LINE__, $e);
                break;

                default:
                    header('HTTP/1.1 503 Service Temporarily Unavailable');
                    header('Status: 503 Service Temporarily Unavailable');

                    if (file_exists(Root.'/Public/down.html'))
                        readfile(Root.'/Public/down.html');
                    else
                        readfile(Codeine.'/down.html');

                    header('X-Exception: '.$e->getMessage());
                    die();
                break;
            }
        }

/*        if (isset($Call['Output']))
            $Call['HTTP']['Headers']['Content-Length:'] = strlen($Call['Output']);*/

        if (isset($Call['HTTP']['Headers']))
            foreach ($Call['HTTP']['Headers'] as $Key => $Value)
                header ($Key . ' ' . $Value);


        F::Run('IO', 'Write', $Call,
            [
                'Storage' => 'Output',
                'Where' => $Call['HTTP']['URL'],
                'Data' => $Call['Output']
            ]);

        F::Log('Interface *Web* finished', LOG_INFO);

        $Call = F::Hook('afterInterfaceRun', $Call);

        return $Call;
    });

    setFn('Redirect', function ($Call)
    {
        $URL = $Call['Location'];

        if (preg_match_all('@\$([\.\w]+)@', $URL, $Vars))
        {
            foreach ($Vars[0] as $IX => $Key)
                $URL = str_replace($Key, F::Dot($Call,$Vars[1][$IX]) , $URL);
        }

        $Call['HTTP']['Headers']['HTTP/1.1'] = ' 301 Moved Permanently';
        $Call['HTTP']['Headers']['Location:'] = $URL;
        $Call['HTTP']['Headers']['Cache-Control:'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';

        F::Log('Redirected to '.$URL, LOG_INFO);

        return $Call;
    });

    setFn('Remote Redirect', function ($Call)
    {
        $URL = $Call['Location'];

        if (preg_match_all('@\$([\.\w]+)@', $URL, $Vars))
            foreach ($Vars[0] as $IX => $Key)
                $URL = str_replace($Key, F::Dot($Call,$Vars[1][$IX]) , $URL);

        if (preg_match('/^http/', $URL))
            ;
        else
            $URL = 'http://'.$URL;

        $Call['HTTP']['Headers']['HTTP/1.1'] = ' 301 Moved Permanently';
        $Call['HTTP']['Headers']['Location:'] = $URL;
        $Call['HTTP']['Headers']['Cache-Control:'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';

        F::Log('Redirected to '.$URL, LOG_INFO);

        return $Call;
    });

    setFn('StoreURL', function ($Call)
    {
        if (isset($Call['Request']['BackURL']))
            $Call['BackURL'] = $Call['Request']['BackURL'];
        elseif (isset($_SERVER['HTTP_REFERER']))
            $Call['BackURL'] = $_SERVER['HTTP_REFERER'];
        else
            $Call['BackURL'] = $Call['HTTP']['URL'];

        if (isset($Call['Session']['BackURL']) && ($Call['BackURL'] == $Call['Session']['BackURL']))
            ;
        else
        {
            if ($Call['BackURL'] == '/')
                ;
            else
            {
                $Call = F::Run('Session', 'Write', $Call, ['Session Data' => ['BackURL' => $Call['BackURL']]]);
                F::Log('Back URL set to *'.$Call['BackURL'].'*', LOG_INFO);
            }
        }

        return $Call;
    });

    setFn('RestoreURL', function ($Call)
    {
        if (isset($Call['Session']['BackURL']) && !empty($Call['Session']['BackURL']))
        {
            F::Run('Session', 'Write', $Call, ['Session Data' => ['BackURL' => null]]);
            $Call = F::Apply('System.Interface.HTTP', 'Redirect', $Call, ['Location' => $Call['Session']['BackURL']]);
        }

        return $Call;
    });

    setFn('Protocol', function ($Call)
    {
        if (isset($Call['Project']['Hosts'][F::Environment()]))
        {
            if (preg_match('/(\S+)\.'.$Call['Project']['Hosts'][F::Environment()].'/', $_SERVER['HTTP_HOST'], $Subdomains)
            && isset($Call['Subdomains'][$Subdomains[1]]))
            {
                $Call = F::Merge($Call, $Call['Subdomains'][$Subdomains[1]]);
                F::Log('Active Subdomain detected: '.$Subdomains[1], LOG_INFO);
            }

            $_SERVER['HTTP_HOST'] = $Call['Project']['Hosts'][F::Environment()];
        }


        if (preg_match('/:/', $_SERVER['HTTP_HOST']))
            list ($_SERVER['HTTP_HOST'], $Call['HTTP']['Port']) = explode(':', $_SERVER['HTTP_HOST']);

        if (
                   (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']))
                or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                or (isset($_SERVER['HTTP_X_HTTPS']) &&$_SERVER['HTTP_X_HTTPS']))
            {
                $Call['HTTP']['Proto'] = 'https://';
                $Call['HTTP']['Host'] = strtolower($_SERVER['HTTP_HOST']);
            }
            else
            {
                $Call['HTTP']['Proto'] = 'http://';
                $Call['HTTP']['Host'] = strtolower($_SERVER['HTTP_HOST']);
            }

        if (isset($Call['HTTP']['Force SSL']) && $Call['HTTP']['Force SSL'] && $Call['HTTP']['Proto'] == 'http://')
            $Call = F::Run(null, 'Redirect', $Call, ['Location' => 'https://'.$Call['HTTP']['Host'].$Call['HTTP']['URI']]);

        F::Log('Protocol is *'.$Call['HTTP']['Proto'].'*', LOG_INFO);
        F::Log('Host is *'.$Call['HTTP']['Host'].'*', LOG_INFO);

        $Call = F::loadOptions($Call['HTTP']['Host'], null, $Call);

        return $Call;
    });

    setFn('Finish', function ($Call)
    {
        if (function_exists('fastcgi_finish_request'))
            fastcgi_finish_request();
        return $Call;
    });