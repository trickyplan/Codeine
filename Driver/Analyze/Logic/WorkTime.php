<?php

function F_WorkTime_Check($Args)
{
    if ( (date('H') >= 9)&&(date('H') <= 18))
        return true;
    return false;
}