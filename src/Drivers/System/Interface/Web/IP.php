<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        $IP = $_SERVER['REMOTE_ADDR'];

        foreach ($Call['IP Headers'] as $Header)
        {
            if (isset($_SERVER['HTTP_'.$Header]))
                $IP = $_SERVER['HTTP_'.$Header];
        }

        if ($IP == '127.0.0.1' && isset($Call['Pingback']))
        {
            if (($IP = F::Run('IO', 'Read', $Call, ['Storage' => 'Cookie', 'Where' => 'DeveloperIP'])) == null)
            {
                $Pingback = file_get_contents($Call['Pingback']);

                preg_match($Call['IP Regex'], $Pingback, $Pockets);
                $IP = $Pockets[0];

                F::Run('IO', 'Write', $Call, ['Storage' => 'Cookie', 'Where' => 'DeveloperIP', 'Data' => $IP]);

                F::Log('Pingback IP: *'.$IP.'* from *'.$Call['Pingback'].'*', LOG_INFO);
            }
            else
                F::Log('Pingback IP: *'.$IP.'* from *Cookie*', LOG_INFO);

        }
        else
            if (isset($Call['Substitute'][$IP]))
            {
                $IP = $Call['Substitute'][$IP];
                F::Log('IP substituted from *'.$IP.'* to '.$IP, LOG_INFO);
            }

        return $IP;
    });