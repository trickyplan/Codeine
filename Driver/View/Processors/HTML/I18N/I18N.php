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
        if (preg_match_all('@<l>(.*)</l>@SsUu',$Call['Input'], $Pockets))
        {
            $Tokens = Data::Read('Locale::ru_RU.UTF-8');

            foreach($Pockets[0] as $IX => $Match)
            {
                $In[] = $Match;

                $Token = $Pockets[1][$IX];

                if ($I18N = Core::getOption($Token, $Tokens))
                    $Out[] = $I18N;
                else
                    $Out[] = '<nrl>'.$Pockets[1][$IX].'</nrl>';
            }

            $Call['Input'] = str_replace($In, $Out, $Call['Input']);
        }
        return $Call['Input'];
    });
