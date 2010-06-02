<?php

    function F_CSSRun_Process($Data)
    {
        $TRs = array();

        if (preg_match_all('@<cssrun>(.*)</cssrun>@SsUu', $Data, $Matches))
        {
            foreach($Matches[0] as $IX => $Match)
                {
                    Page::$CSS[] = $Matches[1][$IX];
                    $Data = str_replace($Match, '', $Data);
                }
        }
        
        if (!empty(Page::$CSS))
            {
                $Data = str_replace('<place>CSSRun</place>',
'<style type="text/css">
 '.implode(';',Page::$CSS).'
</style>',$Data);
                
            }
            else
                $Data = str_replace('<place>CSSRun</place>', '' ,$Data);
            
        Page::$JS = array();
        return $Data;
    }