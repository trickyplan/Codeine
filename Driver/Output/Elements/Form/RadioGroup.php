<?php

function F_RadioGroup_Render($Args)
{      
    // asort($Args['variants']);
    $Options = array();
    if (isset($Args['variants'])) {
        foreach ($Args['variants'] as $Key => $Value) {
            if ($Args['value'] == $Key)
                $Checked = 'checked="checked"';
            else
                $Checked = '';
            
            $Options[] = '<div class="unit size1of2"><input type="radio" class="Radio" name="'.$Args['name'].'" value="'.$Key.'" '.$Checked.' /> &nbsp;'.$Value.'</div>';
        }
        
    }

    $Output = '<fieldset class="Space w90">'.implode('', $Options).'</fieldset>';
    return $Output;
}