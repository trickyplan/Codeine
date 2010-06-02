<?php

    function F_E_Parse ($Input)
    {
       if (preg_match('@(\s*)\:\:(\s*)@SsUu',$Input))
       {
           $Object = new Object ($Input);
           return Page::Fusion('Objects/'.$Object->Scope.'/'.$Object->Scope.'_E', $Object);
       }
       else
           return '';
    }
    
    