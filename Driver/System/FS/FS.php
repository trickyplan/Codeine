<?php

  function F_FS_Listing($Folder)
  {
      $Handle = opendir($Folder);
      $IC = 0;
      
      while ($File = readdir($Handle))
         if ($File{0} != '.')
            $Files[$IC++] = $File;

      closedir($Handle);

      return $Files;
  }