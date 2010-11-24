<?php

function F_UniqueID_Generate($Object)
    {
        $Output = '';
        for ($a=0; $a<8; $a++) $Output.=mt_rand(0,9);
            return $Output;
    }