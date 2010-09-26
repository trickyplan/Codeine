<?php

    if (Client::$Ticket->Get('Type') !== null)
        {           
            Client::$Agent = new Object('_User');
            
            $Result = Code::E('Security/User','Step3', array(), Client::$Ticket->Get('Type'));

            if ($Result !== false)
               {
                    Client::$Ticket->Set('Device', Server::Arg('Device'));
                    Client::Attach($Result);
                    
                    if (!Client::$User->Load($Result))
                    {
                        Client::$User->Name($Result);
                        Client::$User->Add('Type', Client::$Ticket->Get('Type'));
                        Client::$User->Add('Login', $Result);
                        Client::$User->Save();
                    }

                    Client::Redirect('/_User/~'.$Result);
               }
               else
                    Page::Nest('Application/Gate/Failed');
        }
        else
            throw new WTF ('Broken Ticket', 500);