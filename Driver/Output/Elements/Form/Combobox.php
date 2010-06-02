<?php

function F_Combobox_Render($Args)
{
    Page::JSFile('~jQueryUI/ui.js');

    Page::CSSFile('~jQueryUI/themes/base/jquery.ui.base.css');
    Page::CSSFile('~jQueryUI/themes/base/jquery.ui.theme.css');
    Page::CSSFile('~jQueryUI/themes/base/jquery.ui.autocomplete.css');

    Page::JS('$("#'.$Args['id'].'").autocomplete('.Code::E('Process/Encoders','Encode',$Args['variants'],'JSON').');');
    return '<input name="'.$Args['name'].'" class="Textfield" id="'.$Args['id'].'" value="'.$Args['value'].'"/>';
}