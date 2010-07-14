<?php

  function F_For_Fusion($Args)
  {     
    if (preg_match_all('@<for from="([\d]*)" to="([\d]*)">(.*)</for>@SsUu', $Args['Structure'], $Matches))
    {
       foreach ($Matches[0] as $IX => $Match)
       {
           $Result = '';
           for ($a = $Matches[1][$IX]; $a<=$Matches[2][$IX]; $a++)
               $Result.= str_replace('<#>', $a, $Matches[3][$IX]);

           $Args['Structure'] = str_replace($Match, $Result, $Args['Structure']);
       }
    }

    return $Args['Structure'];
  }