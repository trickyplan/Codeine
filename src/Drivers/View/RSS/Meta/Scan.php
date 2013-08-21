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
            $Call['Title'] = F::Merge($Call['Title'], $Pockets[1]);

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Layout'], $Pockets))
            $Call['Keywords'] = F::Merge($Call['Keywords'], $Pockets[1]);

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Layout'], $Pockets))
            $Call['Description'] = implode(PHP_EOL, $Pockets[1]);

        return $Call;
    });