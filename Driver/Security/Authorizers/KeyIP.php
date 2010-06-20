<?php

function F_KeyIP_Check($Args)
{
    if ($Args['True'][0] == Code::E('Process/Hash','Get',array('Data'=>Server::Get('X-Real-IP'))))
        return true;
    else
        return false;
}

function F_KeyIP_Input($Keyword)
{
    return Code::E('Process/Hash','Get',array('Data'=>Server::Get('X-Real-IP')));
}