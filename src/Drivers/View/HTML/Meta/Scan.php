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
            $Call['Title'] = F::Merge($Call['Title'], $Pockets[1]);
            $Call['Output'] = str_replace($Pockets[0], '', $Call['Output']);
        }

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Output'], $Pockets))
        {
            $Call['Keywords'] = F::Merge($Call['Keywords'], $Pockets[1]);
            $Call['Output'] = str_replace($Pockets[0], '', $Call['Output']);
        }

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Output'], $Pockets))
            {
                $Call['Description'] = F::Merge($Call['Description'], $Pockets[1]);
                $Call['Output'] = str_replace($Pockets[0], '', $Call['Output']);
            }

        return $Call;
    });