<?php

    function F_Agent_Parse ($Input)
    {
        $Input = json_decode($Input, true);
        if (($Data = Client::$Face->Get($Input['Key'])) === null)
            $Data = $Input['Default'];
        return $Data;
    }
    
    