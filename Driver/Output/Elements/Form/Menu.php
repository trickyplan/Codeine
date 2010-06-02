<?php

function F_Menu_Render($Args)
{      
    // asort($Args['variants']);

    if (isset($Args['node']->Multiple) and $Args['node']->Multiple == true)
        $Multiple = 'multiple';

    $Options = array();
    if (isset($Args['variants'])) {
        foreach ($Args['variants'] as $Key => $Value) {
            if ($Args['value'] != $Key)
              $StrValue = '';
            else
              $StrValue = 'selected';

            $Options[] = '<option '.$StrValue.' value="'.$Key.'">'.$Value.'</option>';
        }
        
    }

    $Output = '<select size="10" name="'.$Args['name'].'" '.$Multiple.' >'.implode('', $Options).'</select>';
    return $Output;
}