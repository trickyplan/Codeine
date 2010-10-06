<?php

    function F_T_Parse ($Input)
    {
       if (mb_strpos($Input,'::'))
       {
           list($Scope, $Title) = explode('::', $Input);
           $Object = new Object ($Scope);
           if ($Object->Query('=Title='.$Title))
               return View::Fusion('Objects/'.$Object->Scope.'/'.$Object->Scope.'_E', $Object);
           else
               return $Title;
       }
       else
           return 'Un';
    }
    
    