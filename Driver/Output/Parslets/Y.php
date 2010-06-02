<?php

    function F_Y_Parse ($Input)
    {
        $Input = json_decode($Input, true);
        $Object = new Object ($Input['Object']);
        if (($Data =$Object->Get($Input['Key'])) == null)
            $Data = $Input['Default'];
        return $Data;
    }
    
    