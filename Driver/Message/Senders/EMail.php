<?php

function F_EMail_Mount($Args)
{
    return true;
}

function F_EMail_Unmount($Args)
{
    return true;
}

function F_EMail_Send($Args)
{
    $Header  = 'From: www-data@'._Host . "\r\n";
    $Header .= "Subject: ".$Args['Subject']." \r\n";
    $Header .= 'Reply-To: www-data@'._Host."\r\n";
    $Header .= 'Content-Type: text/html; charset="utf-8"'."\r\n";

    $Msg = '<body>'.$Args['Message'].'</body>';

    return mb_send_mail($Args['To']->Get('EMail'), $Args['Subject'], $Msg, $Header);
}