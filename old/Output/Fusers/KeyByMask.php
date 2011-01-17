<?php

  function F_KeyByMask_Fusion ($Args)
  {     
      if (preg_match_all('@<kbm>(.*)</kbm>@SsUu', $Args['Structure'], $Matches))
      {
        $OK = array();
        $OV = array();
        $IC = 0;

        foreach($Matches[0] as $IX => $Match)
        {
            $Output = '';

            if (($Values =$Args['Object']->GetByMask($Matches[1][$IX])) !== null)
            {
                $Outs = array();

                foreach ($Values as $Value)
                    foreach ($Value as $cValue)
                        $Outs[] = $cValue;
                
                $Output = implode(',',$Outs);
                break;
            }

            $OK[$IC] = $Match;
            $OV[$IC++] = $Output;
        }

        $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);
      }

      return $Args['Structure'];
  }