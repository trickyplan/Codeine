<?php

function F_URL_Validate($Args)
{
    return (boolean)(mb_ereg ("^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$", $Args['Value']));
}

function F_URL_Input($Args)
{
    return $Args['Value'];
}

function F_URL_Output($Args)
{
    return $Args['Value'];
}