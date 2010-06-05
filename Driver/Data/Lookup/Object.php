<?php

  function F_Object_Lookup($Data)
  {
     list($Scope, $Query) = explode(OBJSEP, $Data);
     $Collection = new Collection($Scope);
     $Collection->Query($Query);
     $Collection->Load();

     krumo('',$Collection->ValuesOf('Title'));
  }