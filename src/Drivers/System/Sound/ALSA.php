<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Volume.Down', function ($Call)
    {
        shell_exec('amixer -c 0 -- sset Master playback 5%-');

        $Call['Output']['Content'][] = F::Run(null, 'Volume.Get', $Call);

        return $Call;
    });

    self::setFn('Volume.Up', function ($Call)
    {
        shell_exec('amixer -c 0 -- sset Master playback 5%+');
        $Call['Output']['Content'][] = F::Run(null, 'Volume.Get', $Call);

        return $Call;
    });

    self::setFn('Volume.Get', function ($Call)
    {
        preg_match_all('/values=(\d+)/Ssu', shell_exec("amixer -c 0 cget name='Master Playback Volume'"), $Pockets);
        return $Pockets[1][1];
    });

    self::setFn('Volume.Mute', function ($Call)
    {
        shell_exec('amixer -c 0 -- sset Master playback 0%');

        $Call['Output']['Content'][] = F::Run(null, 'Volume.Get', $Call);

        return $Call;
    });