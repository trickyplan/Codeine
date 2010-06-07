<?php

function F_TextareaTags_Render($Args)
{
    if (!isset($Args['value']))
        $Args['value'] = '';
    
    $TAValue = $Args['value'];

    Page::JSFile('~jQuery/Plugins/Textarea.js');
    Page::JS("$('#".$Args['id']."').TextAreaResizer()");

    $Output = '<textarea id="'.$Args['id'].'" name="'.$Args['name'].'" class="Textarea " rows=5>'.$TAValue.'</textarea>';

    return $Output;
}