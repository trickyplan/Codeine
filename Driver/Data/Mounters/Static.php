<?php

function F_Static_Mount ($Args)
{
    return true;
}

function F_Static_Unmount ($Args)
{
    return true;
}

function F_Static_Create ($Args)
{
    return Data::$Data[$Args['DDL']['I']] = $Args['DDL'];
}

function F_Static_Read ($Args)
{
    if (isset(Data::$Data[$Args['DDL']['I']]))
        return Data::$Data[$Args['DDL']['I']];
    else
        return null;
}

function F_Static_Update ($Args)
{
    Data::$Data[$Args['DDL']['I']] = $Args['DDL'];
    return true;
}

function F_Static_Delete ($Args)
{
    if (isset(Data::$Data[$Args['DDL']['I']]))
        unset(Data::$Data[$Args['DDL']['I']]);
    
    return true;
}

function F_Static_Exist ($Args)
{
    return isset(Data::$Data[$Args['DDL']['I']]);
}