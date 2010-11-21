<?php

function F_Maskfield_Render($Args)
{    
    if (is_array($Args['value']))
        $Args['value'] = implode(',',$Args['value']);
    $Args['class'] = 'Textfield Validation';

    $ID = str_replace(':','_', $Args['name']);

    if (!isset($Args['value']))
        $Args['value'] = '';

    View::JSFile('~jQuery/Plugins/MaskedInput.js');
    if (isset($Args['node']->Required))
        $Args['class'].= ' validate(required)';

    if (!isset($Args['node']->Mask))
        $Args['node']->Mask = '';
    
    View::JS('$("#'.$Args['id'].'").mask("'.$Args['node']->Mask.'",{placeholder:"_"});');
    return '<input id="'.$ID.'" name="'.$Args['name'].'" class="'.$Args['class'].'" type="text" value="'.$Args['value'].'" />
        ';
    //hint="<l>Input:Hint:'.$Args['name'].'</l>
    //$("#'.$ID.'").inputHint()
}