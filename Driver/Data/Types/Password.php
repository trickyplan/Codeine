<?php

function F_Password_Validate($Args)
{
    return is_string($Args['Value']);
}

function F_Password_Input($Args)
{
    return Code::E('Process/Hash','Get', $Args['Value']);
}

function F_Password_Output($Args)
{
    return $Args['Value'];
}