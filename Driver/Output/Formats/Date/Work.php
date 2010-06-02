<?php

function F_Work_Format($Args)
{

    $Holidays = array(0,1,8,53,66,120,121,127,128,129,162,283,307,345,364);

    $Z = date('z');
    $Day = date('w');
    $Hour = date('H');

    if (in_array($Day,$Args['RestDays']) or in_array($Z, $Holidays))
        $Result = 'Holiday';
    else
    {
        if ($Hour>=$Args['StartTime'] and $Hour<$Args['EndTime'])
        {
            $Result = 'On';
            if ($Hour>=$Args['StartDinner'] and $Hour<$Args['EndDinner'])
                $Result = 'Dinner';
        }
        else
            $Result = 'Off';
    }

    return '<icon>Shops/'.$Result.'</icon> <l>'.$Result.'</l>';
}