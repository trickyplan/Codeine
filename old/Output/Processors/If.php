<?php

  function F_If_Process($Data)
  {     
    $Data = preg_replace('@<if=false>(.*)</if=false>@SsUu', '', $Data);
    $Data = preg_replace('@<if=true>(.*)</if=true>@SsUu', '$1', $Data);

    return $Data;
  }