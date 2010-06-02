<?php

    $AuthTypes = Core::$Conf['Options']['Authorizers'];

    if (sizeof($AuthTypes)==1)
        self::$ID = $AuthTypes[0];

    if (self::$ID === null)
    {
        foreach ($AuthTypes as $AT)
            Page::AddBuffered(Page::Replace('Application/Gate/Method', array('<AT/>'=>$AT)));

        Page::Flush();
    }
    else
    {
        if (in_array(self::$ID, $AuthTypes))
        {
            Code::E('Security/User','Step1', null, self::$ID);
            Client::$Ticket->Set('Type', self::$ID);
        }
        else
            throw new WTF('Unknown authentification type.', 500);
    }