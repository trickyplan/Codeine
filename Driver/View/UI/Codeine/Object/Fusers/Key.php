<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Key Fuser
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 18.11.10
     * @time 5:51
     */

    self::Fn('Fusion', function ($Call)
    {
        if (preg_match_all('@<k>(.*)</k>@SsUu', $Call['Body'], $Pockets))
            foreach ($Pockets[0] as $IX => $Match)
            {
                $Key = $Pockets[1][$IX];
                $Value = $Call['Data'][$Key];

                $Call['Body'] = str_replace($Match, $Value , $Call['Body']);
            }
        // FIXME Multikeys, isset?
        return $Call['Body'];
    });
