<?php

function F_Date_Validate($Args)
{
    if (strptime($Args['Value'],'%x'))
        return true;
    else
        return false;
}

function F_Date_Input($Args)
{
    list ($Day, $Month, $Year) = explode('.',$Args['Value']);
    return mktime(0,0,0,$Month,$Day,$Year);
}

function F_Date_Output($Args)
{
    return strftime($Args['Value'], '%x');
}