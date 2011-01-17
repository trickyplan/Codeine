<?php

    function F_JSRun_Process($Data)
    {
        $TRs = array();
        $JS = array();

        if (preg_match_all('@<jsrun>(.*)</jsrun>@SsUu', $Data, $Matches))
        {
            foreach($Matches[0] as $IX => $Match)
                {
                    $JS[] = $Matches[1][$IX];
                    $Data = str_replace($Match, '', $Data);
                }
        }
        
        if (!empty(View::$JS))
            {
                $Data = str_replace('<place>JSRun</place>',
'<script type="text/javascript">
 <!--
 // '.Application::$Call.'
    $(document).ready(function()
    {
    '.implode(';',View::$JS).'
    });
-->
</script>',$Data);
                
            }
            else
                $Data = str_replace('<place>JSRun</place>', '' ,$Data);
            
        return $Data;
    }