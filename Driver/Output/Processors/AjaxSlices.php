<?php

   function F_AjaxSlices_Process($Data)
    {
        if (preg_match_all('@<ajaxslice id="(.*)">(.*)</ajaxslice>@SsUu', $Data, $Matches))
        {
            foreach($Matches[0] as $IX => $Match)
                {
                    Page::JS('Do("'.$Matches[1][$IX].'", "'.$Matches[2][$IX].'")');
                    $Data = str_replace($Match, '<div exec="'.$Matches[2][$IX].'" id="'.$Matches[1][$IX].'"></div>', $Data);
                }
        }        
        return $Data;
    }