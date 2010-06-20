<?php

    function F_JSRun_Process($Data)
    {
        $TRs = array();

        if (preg_match_all('@<jsrun>(.*)</jsrun>@SsUu', $Data, $Matches))
        {
            foreach($Matches[0] as $IX => $Match)
                {
                    Page::$JS[] = $Matches[1][$IX];
                    $Data = str_replace($Match, '', $Data);
                }
        }
        
        if (!empty(Page::$JS))
            {
                $Data = str_replace('<place>JSRun</place>',
'<script type="text/javascript">
 <!--
 // '.Application::$Call.'
    $(document).ready(function()
    {
    '.implode(';',Page::$JS).'
    });
-->
</script>',$Data);
                
            }
            else
                $Data = str_replace('<place>JSRun</place>', '' ,$Data);
            
        Page::$JS = array();
        return $Data;
    }