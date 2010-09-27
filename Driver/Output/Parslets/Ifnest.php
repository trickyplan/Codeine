<?php

    function F_Ifnest_Parse ($Input)
    {
       $Input = json_decode($Input);
       if ($Input->Condition)
           return Page::Load ($Input->Layout);
       else
           return '';
    }
    
    