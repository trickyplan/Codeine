<?php

function F_Mail_Mount($Args)
{
    return true;
}

function F_Mail_Unmount($Args)
{
    return true;
}

function F_Mail_Send($Args)
{
    return mb_send_mail($Args['To']->Get('EMail'), $Args['Subject'], $Args['Message'], $Args['From']->Name);
}

function F_Mail_Receive()
{
            
}