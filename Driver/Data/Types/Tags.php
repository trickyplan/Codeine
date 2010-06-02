<?php

function F_Tags_Validate($Args)
{
    return is_string($Args['Value']);
}

function F_Tags_Input($Args)
{
    $Outs = array();
    $Tags = explode(',',$Args['Value']);
    foreach($Tags as $Key => $Value)
        $Outs[] = trim($Value);
    return $Outs;
}

function F_Tags_Output($Args)
{
    return implode(',',$Args['Value']);
}