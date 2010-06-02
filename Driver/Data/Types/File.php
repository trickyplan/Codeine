<?php

function F_Date_Validate($Args)
{
    if (strptime($Args,'%x'))
        return true;
    else
        return false;
}

function F_Date_Input($Args)
{
    return strptime($Args,'%x');
}

function F_Date_Output($Args)
{
    return strftime($Args, '%x');
}