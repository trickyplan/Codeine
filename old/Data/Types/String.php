<?php

function F_String_Validate($Args)
{
    if (isset($Args['Node']->Max) and (mb_strlen($Args['Value'])>$Args['Node']->Max))
        return false;
    
    return is_string($Args['Value']);
}

function F_String_Input($Args)
{
    return $Args['Value'];
}

function F_String_Output($Args)
{
    return $Args['Value'];
}