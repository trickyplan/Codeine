<?php

  function F_Foreach_Fusion($Args)
  {     
    if (preg_match_all('@<foreach="(.*)">(.*)</foreach>@SsUu', $Args['Structure'], $Matches))
    {
       foreach ($Matches[0] as $IX => $Match)
       {
           $Keys = explode(',',$Matches[1][$IX]);
           $Result = '';
           foreach ($Keys as $Index => $Key)
           {
               if (isset($Keys[$Index-1]))
                   $Prev = $Keys[$Index-1];
               else
                   $Prev = null;

               if (isset($Keys[$Index+1]))
                   $Next = $Keys[$Index+1];
               else
                   $Next = null;

               $Result.= str_replace(
                   array('<each/>', '<prev/>', '<next/>'),
                   array ($Key, $Prev, $Next),
                    $Matches[2][$IX]);
           }
           $Args['Structure'] = str_replace($Match, $Result, $Args['Structure']);
       }
       
    }

    return $Args['Structure'];
  }