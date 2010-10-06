<?php

function F_Textarea_Render($Args)
{
    if (!isset($Args['value']))
        $Args['value'] = '';
    
    $TAValue = $Args['value'];

    View::JSFile('~jQuery/Plugins/Textarea.js');
    View::JS("$('#".$Args['id']."').TextAreaResizer()");

    return '<textarea id="'.$Args['id'].'" name="'.$Args['name'].'" class="Textarea" rows=5>'.$TAValue.'</textarea>';
}