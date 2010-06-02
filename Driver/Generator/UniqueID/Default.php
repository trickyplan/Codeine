<?php

function F_Default_Generate($Prefix)
    {
        $Output = '';
        for ($a=0; $a<8; $a++) $Output.=mt_rand(0,9);
            return $Output;
    }