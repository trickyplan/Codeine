<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Title', function ($Call)
    {
        if (preg_match_all('@<subtitle>(.*)<\/subtitle>@SsUu', $Call['Output'], $Pockets))
        {
            $Call['View']['HTML']['Title'] = F::Merge($Call['View']['HTML']['Title'], $Pockets[1]);
            $Call['Output'] = str_replace($Pockets[0], '', $Call['Output']);
        }

        return $Call;
    });

    setFn('Keywords', function ($Call)
    {
        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Output'], $Pockets))
        {
            $Call['View']['HTML']['Keywords'] = F::Merge($Call['View']['HTML']['Keywords'], $Pockets[1]);
            $Call['Output'] = str_replace($Pockets[0], '', $Call['Output']);
        }

        return $Call;
    });

    setFn('Description', function ($Call)
    {
        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Output'], $Pockets))
            {
                $Call['View']['HTML']['Description'] = F::Merge($Call['View']['HTML']['Description'], $Pockets[1]);
                $Call['Output'] = str_replace($Pockets[0], '', $Call['Output']);
            }

        return $Call;
    });