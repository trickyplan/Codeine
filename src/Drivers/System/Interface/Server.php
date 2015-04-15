<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        F::Log('Interface: *Server*', LOG_INFO);

        ini_set('implicit_flush', true);

        $Server = stream_socket_server("tcp://".$Call['HTTP']['Host'].':'.$Call['Port'], $ErrorNo, $ErrorMessage);

        if ($Server)
        {
            F::Log('Server created', LOG_NOTICE);

            while(true)
            {
                $Client = stream_socket_accept($Server, -1);

                if ($Client)
                {
                    $Request = fread($Client, 8192);

                    if (preg_match('@(GET|POST|UPDATE|DELETE|PUT)(.*)HTTP/(.*)@', $Request, $Pockets))
                    {
                        list(, $Call['HTTP']['Method'], $Call['HTTP']['URI'],  $Call['HTTP Version']) = $Pockets;

                        $Call['Run'] = rawurldecode($Call['HTTP']['URI']);

                        $Call['HTTP']['URL'] = parse_url($Call['Run'], PHP_URL_PATH);
                        $Call['HTTP']['URL Query'] = parse_url($Call['Run'], PHP_URL_QUERY);

                        if (empty($Call['HTTP']['URL Query']))
                            F::Log('Empty query string', LOG_INFO);
                        else
                            F::Log('Query string: *'.$Call['HTTP']['URL Query'].'*', LOG_INFO);

                        F::Log('Run String: '.$Call['Run'], LOG_INFO);

                        if (preg_match('@Host: (.*)@', $Request, $Pockets))
                            $Call['HTTP']['Host'] = trim($Pockets[1]);

                        $Call = F::Apply(null, 'Proto', $Call);

                        $Call = F::Run('Code.Flow.Front', 'Run', $Call);

                        F::Log('Request accepted', LOG_INFO);

                        $Headers = 'HTTP/1.1 '.$Call['HTTP']['Headers']['HTTP/1.1'].PHP_EOL;
                        unset($Call['HTTP']['Headers']['HTTP/1.1']);

                        if (isset($Call['HTTP']['Headers']))
                            foreach ($Call['HTTP']['Headers'] as $Key => $Value)
                                $Headers.= $Key . ' ' . $Value.PHP_EOL;

                        $Call['Output'] = $Headers.PHP_EOL.time();

                        fwrite($Client, $Call['Output']);
                    }

                    fclose($Client);
                }
            }
        }
        else
            F::Log('Server creating error', LOG_ERR);

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

            $Call['HTTP']['Host'] = $Call['Project']['Hosts'][F::Environment()];
        }

        F::Log('Protocol is *'.$Call['HTTP']['Proto'].'*', LOG_INFO);
        F::Log('RHost is *'.$Call['HTTP']['Host'].'*', LOG_INFO);
        F::Log('Host is *'.$Call['HTTP']['Host'].'*', LOG_INFO);

        $Call = F::loadOptions($Call['HTTP']['Host'], null, $Call);

        return $Call;
    });