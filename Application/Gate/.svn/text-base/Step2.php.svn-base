<?php

    if (Client::$Ticket->Get('Type') === null)
        throw new WTF('Unspecified authentification type.', 500);

    if (Code::E('Security/User','Step2', null, Client::$Ticket->Get('Type')))
        Page::Add(Code::E('Security/CAPTCHA', 'Generate', array('Ticket'=>Client::$Ticket)), 'CAPTCHA');
    else
        throw new WTF ('Access Denied',4030);