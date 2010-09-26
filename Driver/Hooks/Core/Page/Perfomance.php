<?php

    function F_Perfomance_Hook()
    {
        if (preg_match_all('@<counter>(.*)</counter>@SsUu', Page::Body(), $Matches))
                foreach ($Matches[0] as $IX => $Match)
                    if (isset(Log::$Counters[$Matches[1][$IX]]))
                        Page::Add(Log::$Counters[$Matches[1][$IX]], $Match);
                    else
                        Page::Add(' No', $Match);

        Page::Add(number_format((memory_get_usage()/1024), 0, '.', ' '), 'Memory');
        Page::Add((round(microtime(true)-Core::$StartTime, 3)*1000), 'Timer');

        return true;
    }