<?php

    function F_CSSRun_Process($Data)
    {
        $TRs = array();
        $CSS = array();

        if (preg_match_all('@<cssrun>(.*)</cssrun>@SsUu', $Data, $Matches))
        {
            foreach($Matches[0] as $IX => $Match)
                {
                    $CSS[] = $Matches[1][$IX];
                    $Data = str_replace($Match, '', $Data);
                }
        }
        
        if (!empty(View::$CSS))
            {
                $Data = str_replace('<place>CSSRun</place>',
'<style type="text/css">
 '.implode(';',$CSS).'
</style>',$Data);
                
            }
            else
                $Data = str_replace('<place>CSSRun</place>', '' ,$Data);
            
        return $Data;
    }