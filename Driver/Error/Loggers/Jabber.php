<?php

    function F_Jabber_Output($Messages)
    {
        Message::Mount('Jabber', 'Jabber', 'logger@localhost:5222');

        foreach($Messages as $AppID => $AppMessages)
                foreach($AppMessages as $Message)
                    Message::Send('Jabber', $AppID.': '.$Message[1],$Message[2], 'logger@localhost:5222');
        // FIXME
        return null;
    }
