<?php

  function F_Default_Tagg($Args)
  {
      $TagStr = array();
      $Tags = &$Args['Tags'];
      $URL  = &$Args['URL'];

      $AllTags = array_sum($Tags);

      $IC = 0;

      foreach ($Tags as $Tag => $Count)
          $TagStr[$IC++] = '<a class="unit" style="font-size: '.(1+($Count/$AllTags)).'em;" href="'.$URL.$Tag.'">'.$Tag.'</a>';
      
      return implode('',$TagStr);
  }