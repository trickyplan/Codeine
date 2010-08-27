<?php

    $AuthTypes = Core::$Conf['Options']['Authorizers'];

    if (count($AuthTypes)==1)
        self::$ID = $AuthTypes[0];

    if (self::$ID === null)
    {
        foreach ($AuthTypes as $AT)
            Page::AddBuffered(Page::Replace('Application/Gate/Method', array('<AT/>'=>$AT)));

        Page::Add('', 'CAPTCHA');
        Page::Flush();
    }
    else
    {
        if (in_array(self::$ID, $AuthTypes))
        {
            Code::E('Security/User','Step1', null, self::$ID);
            Page::Add(Code::E('Security/CAPTCHA', 'Generate', array('Ticket'=>Client::$Ticket)), 'CAPTCHA');
            Client::$Ticket->Set('Type', self::$ID);
        }
        else
            throw new WTF('Unknown authentification type.', 500);
    }