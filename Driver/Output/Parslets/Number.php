<?php

    function F_Number_Parse ($Input)
    {
        $Input = json_decode($Input, true);
        if (is_numeric($Input['V']))
            return Code::E('Output/Formats/Number','Format', $Input['V'], $Input['F']);
        else
            return '';
    }
    
    