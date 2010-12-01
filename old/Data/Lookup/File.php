<?php

  function F_File_Lookup($Data)
  {    
     return explode("\n", Data::Read('UserFS', '{"I":"'.$Data.'"}'));
  }