<?php

    function F_Ifnest_Parse ($Input)
    {
       $Input = json_decode($Input);
       if ($Input->Condition)
           return View::Load ($Input->Layout);
       else
           return '';
    }
    
    