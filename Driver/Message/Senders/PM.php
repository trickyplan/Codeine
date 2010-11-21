<?php

    function F_PM_Mount ($Args)
    {
        return true;
    }
    
    function F_PM_Send($Args)
    {
        $NewBlade = new Object ('Message');
        $NewBlade->Name(uniqid($Args['From']->Name,true).uniqid($Args['To']->Name,true));
        $NewBlade->Add('From', $Args['From']->Name);
        $NewBlade->Add('To', $Args['To']->Name);
        $NewBlade->Add('Subject', $Args['Subject']);
        $NewBlade->Add('Message', $Args['Message']);
        $NewBlade->Save();
    }