<?php

  function F_Minify_CSSMinify($Data)
  {
      $Data = str_replace(array("\n",'  ',"\t"),'',$Data);
      $Data = str_replace(': ',':',$Data);
      $Data = str_replace('{ ','{',$Data);
      $Data = str_replace(' }','}',$Data);
      // $Data = preg_replace('@\/\*(.*)\*\/@SsUu','',$Data);
      return $Data;
  }

  function F_Minify_JSMinify($Data)
  {
      return $Data;
  }
