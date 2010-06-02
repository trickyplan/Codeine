<?php

  self::$Collection = new Collection(self::$Name);
  self::$Collection->Query('=CartedBy='.Client::$UID);
  
  if (sizeof(self::$Collection->Names) == 0)
      Page::Nest('Application/_Shared/Cart/Empty');
  else
  {
      Page::Nest('Application/_Shared/Cart/Full');
      include Engine.Apps.'_Shared/List.php';
  }