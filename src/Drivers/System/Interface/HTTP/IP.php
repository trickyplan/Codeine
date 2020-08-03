<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $IP = $_SERVER['REMOTE_ADDR'];

        foreach ($Call['IP']['Headers'] as $Header)
        {
            if (isset($_SERVER['HTTP_'.$Header]))
            {
                $IP = $_SERVER['HTTP_'.$Header];
                break;
            }
        }

        if (preg_match($Call['IP']['Regex']['Local'], $IP))
        {
            F::Log('Private IP *'.$IP.' *detected*', LOG_INFO);
            if (isset($Call['HTTP']['Request']['Headers']['Developer-Ip']))
            {
                $IP = $Call['HTTP']['Request']['Headers']['Developer-Ip'];
                F::Log('IP: *'.$IP.'* from *Headers*', LOG_INFO);
            }
            else
            {
                if (isset($Call['IP']['Pingback']))
                {
                    $Pingback = F::Run('IO', 'Read',
                    [
                        'Storage'   => 'Web',
                        'Where'     => $Call['IP']['Pingback'],
                        'IO One'    => true,
                        'Behaviours'    =>
                        [
                            'Cached'    =>
                            [
                                'Enabled'   => true,
                                'Keys'      => ['Storage', 'Where'],
                                'TTL'       => 3600
                            ]
                        ]
                    ]);

                    if (preg_match($Call['IP']['Regex']['All'], $Pingback, $Pockets))
                        $IP = $Pockets[0];
                    else
                        $IP = '127.0.0.1';

                    F::Log('Pingback IP: *'.$IP.'* from *'.$Call['IP']['Pingback'].'*', LOG_INFO);
                }
            }
        }
        else
            if (isset($Call['IP']['Substitute'][$IP]))
            {
                F::Log('IP substituted from *'.$IP.'* to '.$Call['IP']['Substitute'][$IP], LOG_INFO);
                $IP = $Call['IP']['Substitute'][$IP];
            }

        $Call['HTTP']['IP'] = $IP;
        F::Log('User IP: '.$IP,  LOG_INFO);

        return $Call;
    });