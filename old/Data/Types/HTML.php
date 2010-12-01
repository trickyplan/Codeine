<?php

function F_HTML_Validate($Args)
{
    return is_string($Args['Value']);
}

function F_HTML_Input($Args)
{
    return html_entity_decode(stripslashes($Args['Value']));
}

function F_HTML_Output($Args)
{
    return $Args['Value'];
}