<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Locale Tag
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 22:03
     */

    self::Fn('Process', function ($Call)
    {
        if (preg_match_all('@<exec>(.*)</exec>@SsUu',$Call['Input'], $Pockets))
        {
            foreach($Pockets[0] as $IX => $Match)
            {
                $In[] = $Match;
                $Out[] = Code::Run($Pockets[1][$IX]);
            }

            $Call['Input'] = str_replace($In, $Out, $Call['Input']);
        }
        return $Call['Input'];
    });
