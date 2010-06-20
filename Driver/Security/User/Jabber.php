<?php

function F_Jabber_Login ($Args)
{
    $Token = sha1(uniqid(true).$Args['Login']);
    
    Client::$Ticket->Set('Jabber:Token', $Token);
    Client::$Ticket->Save();
    
    Transport::Mount   ('UserJabber', 'Jabber', 'admin:montenegro@oxbook:5222');
    Transport::Send    ('UserJabber', $Token, $Args['Login'], 'admin@oxbook', false);
    Transport::Unmount ('UserJabber');

    Page::Add(Page::Get('Auth/Jabber'));
}

function F_Jabber_Auth ($Args)
{
    if (!Code::E('Security/CAPTCHA', 'Check', array('Ticket'=>Client::$Ticket)))
        Page::Nest('A/Gate/CAPTCHAFailed');
    if (Client::$Ticket->Get('Jabber:Token') == Server::Get('Jabber'))
        return true;
    else
        return false;
}