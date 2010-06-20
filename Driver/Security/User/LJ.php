<?php

function F_LJ_Login ($Args)
{
    return Code::E('Security/User','Login', array('Ticket'=>Client::$Ticket, 'Login'=>'http://'.Server::Get('Login').'.livejournal.com'), 'OpenID');
}

function F_LJ_Auth ($Args)
{
    return Code::E('Security/User','Auth', array('Ticket'=>Client::$Ticket), 'OpenID');
}