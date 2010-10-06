<?php

    function F_Info_Parse ($Input)
    {
        if (Client::$Level == 0 or !in_array($Input, Client::$User->Get('Info:Readed', false, false)))
        {
            $Object = new Object ('Info', $Input);
            return View::Fusion('Objects/Info/Info_Show', $Object);
        }
        else
            return '';
    }
    
    