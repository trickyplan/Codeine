<?php

function F_TimezoneSelector_Render($Args)
{      
    $Options = array();    

    $TZs = array('−12','−11','−10','−9:30','−9',
        '−8','−7','−6','−5','−4:30','−4','−3:30','−3','−2:30',
        '−2','−1','0', '+1','+2','+3','+3:30','+4','+4:30','+5',
        '+5:30', '+5:45','+6','+6:30','+7','+7:30','+8','+8:30',
        '+8:45','+9','+9:30','+10','+10:30','+11','+11:30','+12',
        '+12:45','+13','+13:45','+14');

    foreach ($TZs as $TZ)
    {
        $Options[] = '<option value="'.$TZ.'"><l>Geo:Timezone:'.$TZ.'</l></option>';
        $Output2.='<l>Geo:Timezone:'.$TZ.'</l> <br/>';
    }

    $Output = '<select name="'.$Args['name'].'">'.implode('', $Options).'</select>';
    return $Output;
}