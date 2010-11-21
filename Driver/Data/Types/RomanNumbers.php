<?php

function F_RomanNumbers_Validate($Args)
{
    return preg_match('@[MDCLXVI]*@SsUu',$Args['Value']);
}

function F_RomanNumbers_Input($Args)
{
    return Code::E('Process/Convertors/Romanizer','ToDec', $Args['Value']);
}

function F_RomanNumbers_Output($Args)
{
    return Code::E('Process/Convertors/Romanizer','FromDec', $Args['Value']);
}