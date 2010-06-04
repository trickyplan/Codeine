<?php

function F_TwitterField_Render($Args)
{    
    if (is_array($Args['value']))
        $Args['value'] = implode(',',$Args['value']);
    $Args['class'] = 'Textfield_Microblog Validation';

    $ID = str_replace(':','_', $Args['name']);

    if (!isset($Args['value']))
        $Args['value'] = '';

    Page::JSFile('~jQuery/Plugins/CharCounter.js');

    if (isset($Args['node']->Required))
        $Args['class'].= ' validate(required)';
    
    Page::JS('$(".Textfield_Microblog").charCounter();');
    return '<input maxlength="160" type="text" autocomplete="off" id="'.$ID.'" name="'.$Args['name'].'" class="'.$Args['class'].'" value="'.$Args['value'].'" /> &nbsp;';
}