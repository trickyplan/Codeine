<?php

    function F_Rater_Parse ($Input)
    {
       if (preg_match('@(\s*)\:\:(\s*)@SsUu',$Input))
       {
           list($Scope, $Name, $Rater) = explode('::', $Input);
           $Object = new Object ($Scope, $Name);
           if ($Object->Load())
           {
               $Rateds = $Object->Get('RatedBy:'.$Rater, false);

               if (in_array(Client::$UID,$Rateds))
                return Page::Fusion('Raters/Already', $Object, array('<rater/>'=>$Rater));
               else
                return Page::Fusion('Raters/Default', $Object, array('<rater/>'=>$Rater));
           }
       }
       else
           return $Input;
    }
    
    