<?php

function F_GetText_Process ($Data)
    {
        $Pockets = array();

        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Data, $Pockets))
        {
            bindtextdomain('Codeine', Root.'locale');
            textdomain('Codeine');

            foreach($Pockets[1] as $IX => $Match)
            {
                $From[$Match] = $Pockets[0][$IX];
                $To[$Match] = gettext($Match);
            }

            $Data = str_replace($From, $To, $Data);
        }

        return $Data;
    }