<?php

  function F_JSONFile_Lookup($Data)
  {    
     return Data::Read('JSONFS', '{"I":"'.$Data.'"}');
  }