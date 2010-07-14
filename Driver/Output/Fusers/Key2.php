<?php

  function F_Key2_Fusion ($Args)
  {
      $Data      = &$Args['Data'];
      
      if (preg_match_all('@<\{>(.*)<k2>(.*)</k2>(.*)<\}>@SsUu', $Args['Structure'], $Matches))
      {
        $OK = array();
        $OV = array();
        $IC = 0;

        foreach($Matches[0] as $IX => $Match)
        {
            $Output = '';

            $Left   = &$Matches[1][$IX];
            $Keys   = &$Matches[2][$IX];
            $Right  = &$Matches[3][$IX];

            if (mb_strpos($Keys, ','))
                $Keys = explode(',', $Keys);
            else
                $Keys = array($Keys);

            foreach($Keys as $Key)
                if (isset($Data[$Key]) and !empty($Data[$Key][0]))
                {
                    if (sizeof($Data[$Key])>1)
                        foreach($Data[$Key] as $cData)
                            $Output.= $Left.$cData.$Right;
                    else
                        $Output = $Left.$Data[$Key][0].$Right;

                    break;
                }

            $OK[$IC] = $Match;
            $OV[$IC++] = $Output;
        }

        $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
      }

      if (preg_match_all('@<k2>(.*)</k2>@SsUu', $Args['Structure'], $Matches))
      {
        $OK = array();
        $OV = array();
        $IC = 0;

        foreach($Matches[0] as $IX => $Match)
        {
            
            $Output = '';
            $Keys   = &$Matches[1][$IX];

            if (mb_strpos($Keys, ','))
                $Keys = explode(',', $Keys);
            else
                $Keys = array($Keys);

            foreach($Keys as $Key)
                if (isset($Data[$Key]))
                {
                    if (is_array($Data[$Key]))
                            $Output = implode(',',$Data[$Key]);
                    else
                        $Output = $Data[$Key];

                    break;
                }

            $OK[$IC] = $Match;
            $OV[$IC++] = $Output;
        }

        $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
      }

      return $Args['Structure'];
  }