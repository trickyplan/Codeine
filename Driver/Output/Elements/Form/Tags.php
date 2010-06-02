<?php

function F_Tags_Render($Args)
{
    $Attr = '';
    foreach($Args as $Key => $Value)
        $Attr.= $Key.'="'.$Value.'" ';

    return '<input class="Textfield Validation" type="text" '.$Attr.'/>';
}