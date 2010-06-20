<?php

    function F_O_Parse ($Input)
    {
        $Input = json_decode($Input, true);
        $Object = new Object ($Input['Object']);
        return Page::Fusion('Objects/'.$Object->Scope.'/'.$Object->Scope.'_'.$Input['Layout'], $Object);
    }
    
    