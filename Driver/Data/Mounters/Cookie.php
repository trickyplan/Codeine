<?php

function F_Cookie_Mount ($Args)
{
    return true;
}

function F_Cookie_Unmount ($Args)
{
    return true;
}

function F_Cookie_Create ($Args)
{
    return setcookie($Args['DDL']['I'],$Args['DDL']['V'],(time()+$Args['DDL']['TTL']),'/','',false, true);
}

function F_Cookie_Read ($Args)
{
    return Server::Get($Args['DDL']['I']);
}

function F_Cookie_Update ($Args)
{
    return setcookie($Args['DDL']['I'],$Args['DDL']['V'],(time()+$Args['DDL']['TTL']),'/','',false, true);
}

function F_Cookie_Delete ($Args)
{
    return setcookie($Args['DDL']['I'],'',0,'/','',false, true);
}

function F_Cookie_Exist ($Args)
{
    return isset($_COOKIE[$Args['DDL']['I']]);
}