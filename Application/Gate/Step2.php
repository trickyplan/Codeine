<?php

    if (Client::$Ticket->Get('Type') === null)
        throw new WTF('Unspecified authentification type.', 500);

    if (Code::E('Security/CAPTCHA', 'Check', array('Ticket'=>Client::$Ticket)))
        {
            if (!Code::E('Security/User','Step2', null, Client::$Ticket->Get('Type')))
                throw new WTF ('Access Denied',4030);
        }
    else
        throw new WTF ('CAPTCHA Failed', 6001);
    