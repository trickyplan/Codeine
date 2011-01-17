<?php

function F_Combobox_Render($Args)
{
    View::JSFile('~jQueryUI/ui.js');

    View::CSSFile('~jQueryUI/themes/base/jquery.ui.base.css');
    View::CSSFile('~jQueryUI/themes/base/jquery.ui.theme.css');
    View::CSSFile('~jQueryUI/themes/base/jquery.ui.autocomplete.css');

    View::JS('$("#'.$Args['id'].'").autocomplete('.Code::E('Process/Encoders','Encode',$Args['variants'],'JSON').');');
    return '<input name="'.$Args['name'].'" class="Textfield" id="'.$Args['id'].'" value="'.$Args['value'].'"/>';
}