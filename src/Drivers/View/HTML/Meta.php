<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Scan', function ($Call)
    {
        if (preg_match_all('@<subtitle>(.*)<\/subtitle>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Title'] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }
        }

        $Call['Output'] = str_replace('<title/>', '<title>'.$Call['Title'].'</title>', $Call['Output']);

        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Description'] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }
        }

        $Call['Output'] = str_replace('<description/>', '<meta name="description" content="'.$Call['Description'].'" />', $Call['Output']);

        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Output'], $Pockets))
            {
                foreach ($Pockets[1] as $IX => $Match)
                {
                    // TODO Придумать синтаксис для сложения.
                    $Call['Keywords'][] = $Match;
                    $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
                }
            }

        $Call['Output'] = str_replace('<keywords/>', '<meta name="keywords" content="'.implode(',',$Call['Keywords']).'" />', $Call['Output']);

        return $Call;
    });