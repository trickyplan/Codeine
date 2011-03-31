<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Tale Logger
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 25.02.11
     * @time 21:45
     */

    self::Fn('Catch', function ($Call)
    {
        $Rules = array(
            'Data.Connect.Failed' => 'Сорвано соединение с Хранилищем <Store>'
        );

        if (isset($Rules[$Call['Data']['Event']]))
        {
            $Body = $Rules[$Call['Data']['Event']];

            if (preg_match_all('@<(.*)>@SsUu', $Body, $Pockets))
            {
                foreach ($Pockets[1] as $IX => $Match)
                    $Body = str_replace($Pockets[0][$IX],$Call['Data'][$Match], $Body);
            }
        }

        echo $Body.'<br/>';
        return ;
    });
