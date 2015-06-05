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

        if ($IP == '127.0.0.1' && isset($Call['IP']['Pingback']))
        {
            if (($IP = F::Run('IO', 'Read', $Call, ['Storage' => 'Cookie', 'Where' => 'DeveloperIP'])) == null)
            {
                $Pingback = F::Run('IO', 'Read',
                [
                    'Storage'   => 'Web',
                    'Where'     => $Call['IP']['Pingback'],
                    'IO One'    => true
                ]);
                preg_match($Call['IP']['Regex'], $Pingback, $Pockets);
                $IP = $Pockets[0];

                F::Run('IO', 'Write', $Call, ['Storage' => 'Cookie', 'Where' => 'DeveloperIP', 'Data' => $IP]);

                F::Log('Pingback IP: *'.$IP.'* from *'.$Call['IP']['Pingback'].'*', LOG_INFO);
            }
            else
                F::Log('Pingback IP: *'.$IP.'* from *Cookie*', LOG_INFO);
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