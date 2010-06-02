<?php

    function F_ColonSeparatedArray_Process($Result)
    {
        $Lines = explode("\n", $Result);

        foreach($Lines as $KV)
            {
                list($K, $V) = explode(":", $KV, 2);
                $Vals[$K] = $V;
            }

        return $Vals;
    }