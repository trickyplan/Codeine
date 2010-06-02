<?php

  function F_Node_Fusion ($Args)
  {
      if (preg_match_all('@<\{>(.*)<n>(.*)</n>(.*)<\}>@SsUu', $Args['Structure'], $Matches))
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
                if (($Data = $Object->GetKeyOf($Key, false)) !== null)
                {
                    foreach($Data as $cData)
                            $Output.= $Left.$cData.$Right;
                    break;
                }

            $OK[$IC] = $Match;
            $OV[$IC++] = $Output;
        }

        $Structure = str_replace($OK, $OV, $Args['Structure']);
      }

      if (preg_match_all('@<n>(.*)</n>@SsUu', $Args['Structure'], $Matches))
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
                if (($Data = $Object->GetKeyOf($Key, false)) !== null)
                {
                    if(sizeof($Data)>1)
                        $Output = implode(',',$Data);
                    else
                        $Output = $Data[0];
                    break;
                }

            $OK[$IC] = $Match;
            $OV[$IC++] = $Output;
        }

        $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
      }

      return $Args['Structure'];
  }