<?php

    function F_gZip_Read_Pre($DL)
    {
        foreach ($DL['Where'] as $Rule => $Conditions)
            foreach ($Conditions as $Key => $Value)
                    $DL['Where'][$Rule][$Key] = gzdeflate($Value);
        return $DL;
    }

    function F_gZip_Read_Post($Result)
    {
        $Output = array();
        if (is_array($Result))
            foreach ($Result as $Key => $Value)
                $Output[$Key] = F_gZip_Read_Post($Value);
        else
            $Output = gzinflate($Result);
        
        return $Output;
    }

    function F_gZip_Create_Pre($DL)
    {
        foreach ($DL as $Key => $Value)
            $DL[$Key] = gzdeflate($Value);
        return $DL;
    }

    function F_gZip_Create_Post($Result)
    {
        return $Result;
    }