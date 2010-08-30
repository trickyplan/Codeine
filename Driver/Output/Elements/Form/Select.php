<?php

    function F_Select_Render($Args)
    {      
        // asort($Args['variants']);
        
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
    
        $Output = '<select class="Select" name="'.$Args['name'].'">'.implode('', $Options).'</select>';
        return $Output;
    }