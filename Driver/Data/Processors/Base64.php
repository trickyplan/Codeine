<?php

    function F_Base64_Read_Pre($DL)
    {
        foreach ($DL['Where'] as $Rule => $Conditions)
            foreach ($Conditions as $Key => $Value)
                    $DL['Where'][$Rule][$Key] = base64_encode($Value);
        return $DL;
    }

    function F_Base64_Read_Post($Result)
    {
        $Output = array();
        if (is_array($Result))
            foreach ($Result as $Key => $Value)
                $Output[$Key] = F_gZip_Read_Post($Value);
        else
            $Output = base64_decode($Result);
        
        return $Output;
    }

    function F_Base64_Create_Pre($DL)
    {
        foreach ($DL as $Key => $Value)
            $DL[$Key] = base64_encode($Value);
        return $DL;
    }