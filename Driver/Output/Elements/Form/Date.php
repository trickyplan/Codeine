<?php

function F_Date_Render($Args)
{
    $Attr = '';
    $Args['id'] = 'DTPCK'.uniqid();

    Page::JSFile('~jQueryUI/ui.js');
    Page::JSFile('~jQueryUI/ui/i18n/jquery.ui.datepicker-ru.js');

    Page::CSSFile('~CSS/jQueryUI/base/jquery.ui.base.css');
    Page::CSSFile('~CSS/jQueryUI/base/jquery.ui.theme.css');
    Page::CSSFile('~CSS/jQueryUI/base/jquery.ui.datepicker.css');
    
    Page::JS('$("#'.$Args['id'].'").datepicker({dateFormat: "dd.mm.yy"});');

    if (!is_numeric($Args['value']))
        $Args['value'] = time();

    return '<input name="'.$Args['name'].'" id="'.$Args['id'].'" class="Textfield Validation" type="text" value="'.date('d.m.Y',$Args['value']).'" />';
}