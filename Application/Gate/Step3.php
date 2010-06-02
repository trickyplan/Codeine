<?php

    if (Client::$Ticket->Get('Type') !== null)
        {
            if (!Code::E('Security/CAPTCHA', 'Check', array('Ticket'=>Client::$Ticket)))
                Page::Body('Заполните поле CAPTCHA правильно, или мы решим, что вы злой робот.');
            
            Client::$Agent = new Object('_User');
            
            $Result = Code::E('Security/User','Step3', array(), Client::$Ticket->Get('Type'));

                if ($Result == 'Authorized')
                   {
                        Client::Attach(Client::$Ticket->Get('MayBe'));
                        Client::Redirect('/_User/~'.Client::$Ticket->Get('MayBe'));
                   }
                   else
                        Page::Nest('Application/Gate/Failed');
        }
        else
            throw new WTF ('Broken Ticket', 500);