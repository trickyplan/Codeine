<?php

function F_MultiCombobox_Render($Args)
{
    $Attr = '';
    foreach($Args['Attr'] as $Key => $Value)
        $Attr.= $Key.'="'.$Value.'" ';

    foreach ($Args['variants'] as $Value)
        $Options[] = '<option value="'.$Value.'">'.$Value.'</option';
        
    $Output = '<select '.$Attr.' multiple>'.implode('',$Options).'</select>';
    return $Output;
}