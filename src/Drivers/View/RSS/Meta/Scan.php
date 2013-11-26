<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Title', function ($Call)
    {
        if (preg_match_all('@<subtitle>(.*)<\/subtitle>@SsUu', $Call['Layout'], $Pockets))
            $Call['Page']['Title'] = F::Merge($Call['Page']['Title'], $Pockets[1]);

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Layout'], $Pockets))
            $Call['Page']['Keywords'] = F::Merge($Call['Page']['Keywords'], $Pockets[1]);

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Layout'], $Pockets))
            $Call['Page']['Description'] = implode(PHP_EOL, $Pockets[1]);

        return $Call;
    });