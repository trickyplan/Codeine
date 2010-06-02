<?php

function F_Rambler_Login ($Args)
{
    return Code::E('Security/User','Login', array(
        'Ticket' => Client::$Ticket,
        'Login'  => 'id.rambler.ru/users/'.$Args['Login'].'/'),
    'OpenID');
}

function F_Rambler_Auth ($Args)
{
    return Code::E('Security/User','Auth', array('Ticket'=>Client::$Ticket), 'OpenID');
}