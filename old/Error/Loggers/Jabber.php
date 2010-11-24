<?php

    function F_Jabber_Initialize($Args)
    {
        return Message::Mount('JabberLog', 'Jabber', $Args);
    }

    function F_Jabber_Info($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Error($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Warning($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Bad($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Good ($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Dump($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Important($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Stage($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Hint($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Perfomance($Args)
    {
        Message::Send('JabberLog', $Args['Message'], 'logger@localhost:5222');
    }

    function F_Jabber_Shutdown($Logger)
    {
        return Message::Unmount('JabberLog');
    }