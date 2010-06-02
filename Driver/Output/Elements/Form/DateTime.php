<?php

function F_DateTime_Render($Args)
{
    $Attr = '';
    $Args['id'] = 'DTPCK'.uniqid();

    Page::JSFile('~jQueryUI/ui.js');
    Page::JSFile('~jQueryUI/ui/jquery.ui.datetimepicker.js');
    Page::JSFile('~jQueryUI/ui/i18n/jquery.ui.datepicker-ru.js');

    Page::CSSFile('~CSS/jQueryUI/base/jquery.ui.base.css');
    Page::CSSFile('~CSS/jQueryUI/base/jquery.ui.theme.css');
    Page::CSSFile('~CSS/jQueryUI/base/jquery.ui.datetimepicker.css');
    
    Page::JS('$("#'.$Args['id'].'").datetimepicker();');

    if (!is_numeric($Args['value']))
        $Args['value'] = time();

    return '<input name="'.$Args['name'].'" id="'.$Args['id'].'" class="Textfield Validation" type="text" value="'.date('d.m.Y',$Args['value']).'" />';
}