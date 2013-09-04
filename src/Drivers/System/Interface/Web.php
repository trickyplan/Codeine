<?php

    /* Codeine
     * @author BreathLess
     * @description Web Interface 
     * @package Codeine
     * @version 7.1
     */

    setFn ('Do', function ($Call)
    {
        if (isset($_FILES['Data']))
        {
            foreach ($_FILES['Data'] as $Key => $Value)
                foreach ($Value as $IX => $cValue)
                    foreach ($cValue as $C2 => $V2)
                        $_REQUEST['Data'][$IX][$C2][$Key] = $V2;
            // FUCK!
        }
        if (!in_array($_SERVER['REQUEST_METHOD'], $Call['HTTP']['Methods']['Allowed']))
            $_SERVER['REQUEST_METHOD'] = $Call['HTTP']['Methods']['Default'];

        $Call['HTTP Method'] = $_SERVER['REQUEST_METHOD'];

        F::Log('Method: '.$Call['HTTP Method'], LOG_INFO);

        $Call['Request'] = $_REQUEST;
        $Call['Cookie'] = $_COOKIE;

        $Call['Run'] = rawurldecode($_SERVER['REQUEST_URI']);
        $Call['URL Query'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $Call['URI'] = rawurldecode($_SERVER['REQUEST_URI']).(empty($Call['URL Query'])? '?' : '');
        $Call['URL'] = parse_url($Call['URI'], PHP_URL_PATH);

        $Call = F::Hook('beforeInterfaceRun', $Call);

        if (!isset($Call['Skip Run']))
        {
            $Call = F::Run(null, 'Protocol', $Call);

            $Call = F::Run($Call['Service'], $Call['Method'], $Call);
        }

        if (isset($Call['Headers']))
            foreach ($Call['Headers'] as $Key => $Value)
                header ($Key . ' ' . $Value);

        $Call = F::Hook('afterInterfaceRun', $Call);

        if (isset($Call['Output']))
            $Call = F::Live ($Call['Interface']['Output'], $Call, ['Data' => $Call['Output']]);

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

        $Call['Headers']['HTTP/1.1'] = ' 301 Moved Permanently';
        $Call['Headers']['Location:'] = $URL;

        F::Log('Redirected to '.$URL, LOG_INFO);

        return $Call;
    });

    setFn('StoreURL', function ($Call)
    {
        if(!isset($Call['Request']['BackURL']) && isset($_SERVER['HTTP_REFERER']))
            $Call['BackURL'] = $_SERVER['HTTP_REFERER'];
        else
            $Call['BackURL'] = $Call['Request']['BackURL'];

        F::Log('Back URL set to *'.$Call['BackURL'].'*', LOG_INFO);

        return $Call;
    });

    setFn('RestoreURL', function ($Call)
    {
        if (isset($Call['Request']['BackURL']) && !empty($Call['Request']['BackURL']))
            $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => $Call['Request']['BackURL']]);
        elseif (isset($_SERVER['HTTP_REFERER']))
            $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => $_SERVER['HTTP_REFERER']]);

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

        if ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) or
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
            {
                $Call['Proto'] = 'https://';
                $Call['Host'] = 'https://'.$_SERVER['HTTP_HOST'];
                $Call['RHost'] = $_SERVER['HTTP_HOST'];
            }
            else
            {
                $Call['Proto'] = 'http://';
                $Call['Host'] = 'http://'.$_SERVER['HTTP_HOST'];
                $Call['RHost'] = $_SERVER['HTTP_HOST'];
            }

        return $Call;
    });