<?php

function F_Numberfield_Render($Args)
{    
    if (is_array($Args['value']))
        $Args['value'] = implode(',',$Args['value']);
    
    $Args['class'] = 'Textfield Validation Numberfield';

    $ID = str_replace(':','_', $Args['name']);

    if (!isset($Args['value']))
        $Args['value'] = '';

    Page::JSFile('~jQuery/Plugins/Textfield.js');
    Page::JSFile('~jQuery/Plugins/Numeric.js');
    
    if (isset($Args['node']->Required))
        $Args['class'].= ' validate(required)';
    
    Page::JS('$(".Numberfield").autoGrowInput({comfortZone: 50,minWidth: 50,maxWidth: 200}).numeric();');
    return '<input id="'.$ID.'" name="'.$Args['name'].'" class="'.$Args['class'].'" type="text" value="'.$Args['value'].'"  />';
}