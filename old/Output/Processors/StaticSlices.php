<?php

   function F_StaticSlices_Process($Data)
    {
        if (preg_match_all('@<staticslice>(.*)</staticslice>@SsUu', $Data, $Matches))
        {
            foreach($Matches[0] as $IX => $Match)
                $Data = str_replace($Match, file_get_contents($Matches[1][$IX]), $Data);
        }        
        return $Data;
    }