<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Title', function ($Call)
    {
        $Call['Title'] = [];
        if (preg_match_all('@<subtitle>(.*)<\/subtitle>@SsUu', $Call['Layout'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                $Call['Title'][] = $Match;
            }
        }

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Layout'], $Pockets))
        {
            $Call['Keywords'] = [];
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Keywords'][] = $Match;
            }
        }

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Layout'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Description'] = $Match;
            }
        }

        return $Call;
    });