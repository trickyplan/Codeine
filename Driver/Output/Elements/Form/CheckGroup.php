<?php

function F_CheckGroup_Render($Args)
{      
    // asort($Args['variants']);
    
    $Options = array();
    if (!is_array($Args['value']))
        $Args['value'] = array($Args['value']);
    
    if (isset($Args['variants'])) {
        foreach ($Args['variants'] as $Key => $Value)
        {
             if (in_array($Key, $Args['value']))
                $Checked = 'Checked';
             else
                 $Checked = '';
             
             $Options[] = '<div class="unit size1of2"> <input name="'.$Args['name'].'" type="checkbox" value="'.$Key.'" '.$Checked.' /> '.$Value.'</div>';
        }
    }

    $Output = '<fieldset class="Space w70">'.implode('', $Options).'</fieldset>';
    return $Output;
}