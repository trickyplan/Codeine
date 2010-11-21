<?php

    function F_Agent_Parse ($Input)
    {
        if (Client::$Level == 2) 
            {
                $Input = json_decode($Input, true);
                if (($Data = Client::$Face->Get($Input['Key'])) === null)
                    $Data = $Input['Default'];
            }
        else
            $Data = null;
        return $Data;
    }
    
    