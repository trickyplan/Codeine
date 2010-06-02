<?php

  function F_Tag_Fusion ($Args)
  {
      $Data      = &$Args['Data'];

      if (preg_match_all('@<\{>(.*)<tag>(.*)</tag>(.*)<\}>@SsUu', $Args['Structure'], $Matches))
      {
        $OK = array();
        $OV = array();
        $IC = 0;

        foreach($Matches[0] as $IX => $Match)
        {
            $Output = array();
            $Key   = &$Matches[2][$IX];

            if (isset($Data[$Key]))
            {
                foreach ($Data[$Key] as $Value)
                     $Output[] = '<icon>UI/Tag</icon> <a href="/web/'.Application::$Name.'/List/='.$Key.'='.$Value.'">'.$Value.'</a>';
            }

            $OK[$IC] = $Match;
            $OV[$IC++] = $Matches[1][$IX].implode(', ',$Output).$Matches[3][$IX];
        }

        $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
      }

      if (preg_match_all('@<tag>(.*)</tag>@SsUu', $Args['Structure'], $Matches))
      {
        $OK = array();
        $OV = array();
        $IC = 0;

        foreach($Matches[0] as $IX => $Match)
        {
            $Output = array();
            $Key   = &$Matches[1][$IX];

            if (isset($Data[$Key]))
            {
                foreach ($Data[$Key] as $Value)
                     $Output[] = '<a href="/'.Application::$Name.'/='.$Key.'='.$Value.'">'.$Value.'</a>';
            }

            $OK[$IC] = $Match;
            $OV[$IC++] = implode(', ',$Output);
        }

        $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
      }

      return $Args['Structure'];
  }