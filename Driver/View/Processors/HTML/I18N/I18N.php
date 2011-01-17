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
            $Tokens = Data::Read(
                    array(
                        'Point' => 'Locale',
                        'Where' =>
                            array(
                                'ID'=>'ru_RU.UTF-8')));

            foreach($Pockets[0] as $IX => $Match)
            {
                $In[] = $Match;

                if (isset($Tokens[$Pockets[1][$IX]]))
                    $Out[] = $Tokens[$Pockets[1][$IX]];
                else
                    $Out[] = '<nrl>'.$Pockets[1][$IX].'</nrl>';
            }

            $Call['Input'] = str_replace($In, $Out, $Call['Input']);
        }
        return $Call['Input'];
    });