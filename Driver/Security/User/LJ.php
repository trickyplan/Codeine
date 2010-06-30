<?php

function F_LJ_Step1 ($Args)
{
    Page::Nest('Application/Gate/Input');
}

function F_LJ_Step2 ($Args)
{
    return Code::E('Security/User','Step2', array('Ticket'=>Client::$Ticket, 'Login'=>Server::Get('Login').'.livejournal.com'), 'OpenID');
}

function F_LJ_Step3 ($Args)
{
    return Code::E('Security/User','Step3', array('Ticket'=>Client::$Ticket), 'OpenID');
}