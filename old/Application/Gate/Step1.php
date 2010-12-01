<?php

    $AuthTypes = Core::$Conf['Drivers']['Installed']['Security/User'];

    if (count($AuthTypes)==1)
        self::$ID = $AuthTypes[0];

    if (self::$ID === null)
    {
        foreach ($AuthTypes as $AT)
            View::AddBuffered(View::Replace('Application/Gate/Method', array('<AT/>'=>$AT)));

        View::Add('', 'CAPTCHA');
        View::Flush();
    }
    else
    {
        if (in_array(self::$ID, $AuthTypes))
        {
            Code::E('Security/User','Step1', null, self::$ID);
            View::Add(Code::E('Security/CAPTCHA', 'Generate', array('Ticket'=>Client::$Ticket)), 'CAPTCHA');
            Client::$Ticket->Set('Type', self::$ID);
        }
        else
            throw new WTF('Unknown authentification type.', 500);
    }