<?php

function F_Date_Check($Args)
{
    if (preg_match('@\d{1,2}\.\d{1,2}\.\d{4}@SsUu', $Args['Value']))
        return true;
    else
        return 'Invalid Date Format';
}