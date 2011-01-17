<?php

  function F_Owner_Fusion($Args)
  {

    if (preg_match_all('@<owner>(.*)</owner>@SsUu', $Args['Structure'], $Matches))
    {
        $IC = 0;

        if (Client::$Authorized and $Args['Object']->Get('Owner') == Client::$UID)
        {
            foreach($Matches[0] as $IX => $Match)
            {
                $OK[$IC]   = $Match;
                $OV[$IC++] = $Matches[1][$IX];
            }
        }
        else
            foreach($Matches[0] as $IX => $Match)
            {
                $OK[$IC]   = $Match;
                $OV[$IC++] = '';
            }

       $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
    }

    return $Args['Structure'];
  }