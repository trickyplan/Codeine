<?php

function F_Checkbox_Render($Args)
{
    $Attr = '';
    $Checked = '';
    if ($Args['value'] == 'True') 
        $Checked = 'checked';
    else  
        $Args['value'] = 'True';
        
    foreach($Args as $Key => $Value)
        $Attr.= $Key.'="'.$Value.'" ';
        
    return '<input type="checkbox" '.$Attr.' '.$Checked.' />';
}