<?php

    function F_Info_Parse ($Input)
    {
        if (!in_array($Input, Client::$User->Get('Info:Readed', false)))
        {
            $Object = new Object ('Info', $Input);
            return Page::Fusion('Objects/Info/Info_Show', $Object);
        }
        else
            return '';
    }
    
    