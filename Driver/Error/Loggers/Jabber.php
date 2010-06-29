<?php

    function F_Jabber_Output($Messages)
    {
        Transport::Mount('Jabber', 'Jabber', 'logger@localhost:5222');

        foreach($Messages as $Message)
            Transport::Send('Jabber', $Message[1],$Message[2], 'logger@localhost:5222');
        // FIXME
        return null;
    }
