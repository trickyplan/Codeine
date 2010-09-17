<?php

  function F_Formalin_Parse($Data)
  {
      $Data = json_decode($Data);
      $Data['Model'];
      $Data['Set'];
      $Data['Layout'];
      $Data['Data'];
      $Data['URL'];

      $Output = '';

      foreach($Model->Nodes as $Name => $Field)
      {

      }
      
      return ;
  }