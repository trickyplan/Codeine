<?php

    function F_OSelect_Render($Args)
    {              
        $Options = array();    
        if (isset($Args['variants'])) {
            foreach ($Args['variants'] as $Key => $Value) {
                if ($Args['value'] != $Key)
                  $StrValue = '';
                else
                  $StrValue = 'selected';
    
                $Object = new Object($Value);
                $Options[] = '<option '.$StrValue.' value="'.$Key.'">'.$Object->GetOr(array('Title','Login')).'</option>';
            }
            
        }
    
        $Output = '<select class="Select" name="'.$Args['name'].'">'.implode('', $Options).'</select>';
        return $Output;
    }