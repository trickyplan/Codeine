<?php

  function F_XML_Route($Call)
  {
      $Routed = null;

      if (mb_substr($Call,0,5) == '<?xml')
      {
        $Call = simplexml_load_string($Call);
        if ($Call)
        {
            $Routed = array();
            foreach($XML as $Key => $Value)
                $Routed[(string) $Key] = (string) $Value;
        }
      }

      return $Routed;
  }