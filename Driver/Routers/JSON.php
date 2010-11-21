<?php

  function F_JSON_Route($Call)
  {
        $Routed = array();

        $Call = json_decode($Call, true);

        if (null !== $Call)
            foreach($Call as $Key => $Value)
                $Routed[$Key] = $Value;
        else
            $Routed = null;

        return $Routed;
  }