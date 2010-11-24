<?php

function F_Yandex_Login ($Args)
{
    return Code::E('Security/User','Login', array(
        'Ticket' => Client::$Ticket,
        'Login'  => 'http://openid.yandex.ru/'.$Args['Login'].'/'),
    'OpenID');
}

function F_Yandex_Auth ($Args)
{
    return Code::E('Security/User','Auth', array('Ticket'=>Client::$Ticket), 'OpenID');
}