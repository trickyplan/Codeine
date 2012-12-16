<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Title', function ($Call)
    {
        if (preg_match_all('@<subtitle>(.*)<\/subtitle>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                $Call['Title'][] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }
        }

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Keywords'][] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }
        }

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Description'] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }
        }

        return $Call;
    });