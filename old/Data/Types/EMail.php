<?php

function F_EMail_Validate($Args)
{
    return (boolean)(mb_ereg ("^[a-zA-Z0-9_'+*/^&=?~{}\-](\.?[a-zA-Z0-9_'+*/^&=?~{}\-])*\@((\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(\:\d{1,3})?)|(((([a-zA-Z0-9][a-zA-Z0-9\-]+[a-zA-Z0-9])|([a-zA-Z0-9]{1,2}))[\.]{1})+([a-zA-Z]{2,6})))$", $Args['Value']));
}

function F_EMail_Input($Args)
{
    return $Args['Value'];
}

function F_EMail_Output($Args)
{
    return $Args['Value'];
}