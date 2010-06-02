<?php

  function F_Default_CSSMinify($Data)
  {
      $Data = str_replace(array("\n",'  ',"\t"),'',$Data);
      $Data = str_replace(': ',':',$Data);
      $Data = str_replace('{ ','{',$Data);
      $Data = str_replace(' }','}',$Data);
      // $Data = preg_replace('@\/\*(.*)\*\/@SsUu','',$Data);
      return $Data;
  }

  function F_Default_JSMinify($Data)
  {
      return $Data;
  }
