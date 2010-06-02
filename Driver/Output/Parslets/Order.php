<?php

    function F_Order_Parse ($Input)
    {
        if (($JSON = json_decode($Input,true))!==null)
           {
               $Buffer = '';
               foreach ($JSON as $Key => $Value)
               {
                   $Object = new Object ($Key);
                   $Buffer.= str_replace('<Count/>', $Value, Page::Fusion('Objects/'.$Object->Scope.'/'.$Object->Scope.'_Order', $Object));
               }
               return $Buffer;
           }
       else
           return '';
    }
    
    