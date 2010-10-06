<?php

function F_Password_Render($Args)
{
    if (is_array($Args['value']))
        $Args['value'] = implode(',',$Args['value']);
    $Args['class'] = 'Textfield Validation';

    $ID = str_replace(':','_', $Args['name']);

    if (!isset($Args['value']))
        $Args['value'] = '';

    View::JSFile('~jQuery/Plugins/Textfield.js');
    View::JS('$(".Textfield").autoGrowInput({comfortZone: 50,minWidth: 100,maxWidth: 400});');
    return '<input id="'.$ID.'" name="'.$Args['name'].'" class="'.$Args['class'].'" type="password" value="'.$Args['value'].'" />';
}