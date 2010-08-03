<?php

  function F_ZendStyle_Route($Call)
  {
        $Query = '';
        $Routed = array();

        $Call = explode ('/', mb_substr($Call,1));

        $Pieces = sizeof($Call);

        if ($Pieces % 2 == 1)
        {
            list ($Routed['Interface'], $Routed['Name'], $Routed['Plugin']) = $Call;
            
            for ($ic = 4; $ic <= $Pieces; $ic+=2)
                $Routed[$Call[$ic]] = $Call[$ic+1];
        }
        else
            $Routed = null;
        
      return $Routed;
  }