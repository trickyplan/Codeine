<?php

function F_MK_Login ($Args)
{
    return Code::E('Security/User','Login', array('Ticket'=>Client::$Ticket, 'Login'=>$Args['Login'].'.moikrug.ru'), 'OpenID');
}

function F_MK_Auth ($Args)
{
    return Code::E('Security/User','Auth', array('Ticket'=>Client::$Ticket), 'OpenID');
}