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

               if ($Object->Get('Rating:'.$Rater) >= 0)
                   $State = 'Positive';
               else
                   $State = 'Negative';

               if (in_array(Client::$UID,$Rateds))
                return View::Fusion('Raters/Already', $Object, array('<rater/>'=>$Rater, '<state/>'=>$State));
               else
                return View::Fusion('Raters/Default', $Object, array('<rater/>'=>$Rater, '<state/>'=>$State));
           }
       }
       else
           return $Input;
    }
    
    