<?php

    function F_Cut_Parse ($Inputs)
    {
        $Words = explode(' ',$Inputs);
        if (sizeof($Words) > 20)
            return mb_substr($Inputs, 0, mb_strpos($Inputs, $Words[20])).'...';
        else
            return $Inputs;
    }
    
    