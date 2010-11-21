<?php 

function F_Parslet_Process ($Data)
{
    $Pockets = array();
    while (preg_match_all('@<vx(.*)>(.*)<\/vx(.*)>@SsUu', $Data, $Pockets))
        foreach ($Pockets[0] as $IX => $Match)
            $Data = str_replace($Match, Code::E('Output/Parslets','Parse',$Pockets[2][$IX],ucfirst($Pockets[1][$IX])), $Data);

    return $Data;
} 
