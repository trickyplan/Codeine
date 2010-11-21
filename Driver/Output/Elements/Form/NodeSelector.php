<?php

function F_NodeSelector_Render($Args)
{      
    $Options = array();
    $Args['name'] = ((string)$Args['name']);
    $Args['variants'] = array_keys(Client::$Face->GetByMask('^'.$Args['name'].':'));
    
    if (isset($Args['variants']))
    {
        foreach ($Args['variants'] as $Value)
            $Options[] = '<div class="unit size1of4"> <input type="checkbox" class="Checkbox" name="'.$Args['name'].'[]" value="'.$Value.'" /><l>'.$Value.'</l></div>';
    }

    $Output = '<fieldset class="Space">'.implode('', $Options).'</fieldset>';
    return $Output;
}