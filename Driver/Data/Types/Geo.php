<?php

function F_Geo_Validate($Args)
{
    return is_float((float)$Args['Value']);
}

function F_Geo_Input($Args)
{
    return $Args['Value'];
}

function F_Geo_Output($Args)
{
    return $Args['Value'];
}