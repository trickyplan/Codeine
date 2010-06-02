<?php

function F_Float_Validate($Args)
{
    return is_float((float)$Args['Value']);
}

function F_Float_Input($Args)
{
    return $Args['Value'];
}

function F_Float_Output($Args)
{
    return $Args['Value'];
}