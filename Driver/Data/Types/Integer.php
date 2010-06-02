<?php

function F_Integer_Validate($Args)
{
    return is_integer((int) $Args['Value']);
}

function F_Integer_Input($Args)
{
    return $Args['Value'];
}

function F_Integer_Output($Args)
{
    return $Args['Value'];
}