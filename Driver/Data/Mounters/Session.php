<?php

function F_Session_Mount ($Args)
{
    return session_start();
}

function F_Session_Unmount ($Args)
{
    return true;
}

function F_Session_Create ($Args)
{
    return $_SESSION[$Args['DDL']['I']] = $Args['DDL']['V'];
}

function F_Session_Read ($Args)
{
    return Server::Get($Args['DDL']['I']);
}

function F_Session_Update ($Args)
{
    return $_SESSION[$Args['DDL']['I']] = $Args['DDL']['V'];
}

function F_Session_Delete ($Args)
{
    unset($_SESSION[$Args['DDL']['I']]);
        return true;
}

function F_Session_Exist ($Args)
{
    if (isset($_SESSION[$Args['DDL']['I']]))
        return true;
}