<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Scan', function ($Call)
    {
        if (isset($Call['Title']))
            $Call['Title'] = (array) $Call['Title'];
        else
            $Call['Title'] = array();

        $Call['Title'][] = F::loadOptions('Project')['Title'];

        if (preg_match_all('@<subtitle>(.*)<\/subtitle>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                $Call['Title'][] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }

            if ($Call['Reverse'])
                $Call['Title'] = array_reverse($Call['Title']);
        }

        $Call['Title'] = implode($Call['Delimiter'], $Call['Title']);

        $Call['Output'] = str_replace('<title/>', '<title>'.$Call['Title'].'</title>', $Call['Output']);

        if (preg_match_all('@<description>(.*)<\/description>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Description'] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }

            $Call['Output'] = str_replace('<description/>', '<meta name="description" content="'.$Call['Description'].'" />', $Call['Output']);
        }
        else
            $Call['Output'] = str_replace('<description/>', '<meta name="description" content="'.$Call['Title'].'" />', $Call['Output']);

        if (preg_match_all('@<keyword>(.*)<\/keyword>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
            {
                // TODO Придумать синтаксис для сложения.
                $Call['Keywords'][] = $Match;
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }

            $Call['Output'] = str_replace('<keywords/>', '<meta name="keywords" content="'.implode(',',$Call['Keywords']).'" />', $Call['Output']);
        }
        else
            $Call['Output'] = str_replace('<keywords/>', '<meta name="keywords" content="'.$Call['Title'].'" />', $Call['Output']);

        return $Call;
    });