<?php

function F_Text_Validate($Args)
{
    return is_string($Args['Value']);
}

function F_Text_Input($Args)
{
    return $Args['Value'];
}

function F_Text_Output($Args)
{
    return htmlspecialchars_decode($Args['Value']);
}