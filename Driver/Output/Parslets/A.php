<?php

    function F_A_Parse ($Input)
    {
       if (preg_match('@(\s*)\:\:(\s*)@SsUu',$Input))
       {
           $Object = new Object ($Input);
           if ($Object->Load())
               return '/'.$Object->Scope().'/'.$Object->Name();
           else
               return $Input;
       }
       else
           return $Input;
    }
    
    