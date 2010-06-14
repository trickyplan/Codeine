<?php

    function F_Date_Parse ($Input)
    {
        $Input = json_decode($Input, true);

        if (isset( $Input['F']))
            $F = $Input['F'];
        else
            $F = 'Moon';

        if (!is_numeric($Input['D']))
            $Input['D'] = strtotime($Input['D']);

        if (isset($Input['D']) and $Input['D'] !== false)
            $Arg = $Input['D'];
        else
            $Arg = time();


        
        if (isset($Input['Args']))
            $Arg = $Input['Args'];

        return Code::E('Output/Formats/Date','Format', $Arg, $F);
    }
    
    