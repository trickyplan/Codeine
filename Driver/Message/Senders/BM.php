<?php

    function F_BM_Mount ($Args)
    {
        return true;
    }
    
    function F_BM_Send($Args)
    {
            $Message = new Object ('Message',uniqid('MBM'.$Args['To']->Name,true));
            $Message->Add('CreatedOn',time());
            $Message->Add('Method','BM');

            $Message->Add('Subject',$Args['Subject']);
            $Message->Add('Message',$Args['Message']);

            $Message->Add('RType',$Args['To']->Scope);
            $Message->Add('RName',$Args['To']->Name);

            $Message->Add('SType',$Args['From']->Scope);
            $Message->Add('SName',$Args['From']->Name);

            if (!isset($Args['Type']))
                $Args['Type'] = 'Message';
            
            $Message->Add('Type',$Args['Type']);

            $Message->Add('Owner', $Args['To']->Get('Owner'));
            $Message->Add('Sended','True');

            $Message->Save();
    }