<?php

    function F_Jabber_Output($Messages)
    {
        Transport::Mount('Jabber', 'Jabber', 'admin:admin@bth-laptop:5222');

        foreach($Messages as $Message)
            Transport::Send('Jabber', $Message[1],$Message[2], 'rusbreathless@jabber.ru');
        
        return null;
    }
