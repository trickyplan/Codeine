<?php

    function F_CSA_Encode($Result)
    {
        $Lines = explode("\n", $Result);

        foreach($Lines as $KV)
            {
                list($K, $V) = explode(":", $KV, 2);
                $Vals[$K] = $V;
            }

        return $Vals;
    }

    function F_CSA_Decode($Result)
    {
        // TODO CSA Decoe
        return Log::Warning('Not implemented');
    }