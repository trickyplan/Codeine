<?php

function F_DateTime_Render($Args)
{
    $Attr = '';
    $Args['id'] = 'DTPCK'.uniqid();

    View::JSFile('~jQueryUI/ui.js');
    View::JSFile('~jQueryUI/ui/jquery.ui.datetimepicker.js');
    View::JSFile('~jQueryUI/ui/i18n/jquery.ui.datepicker-ru.js');

    View::CSSFile('~CSS/jQueryUI/base/jquery.ui.base.css');
    View::CSSFile('~CSS/jQueryUI/base/jquery.ui.theme.css');
    View::CSSFile('~CSS/jQueryUI/base/jquery.ui.datetimepicker.css');
    
    View::JS('$("#'.$Args['id'].'").datetimepicker();');

    if (!is_numeric($Args['value']))
        $Args['value'] = time();

    return '<input name="'.$Args['name'].'" id="'.$Args['id'].'" class="Textfield Validation" type="text" value="'.date('d.m.Y',$Args['value']).'" />';
}