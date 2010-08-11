<?php

function F_BigUID_Generate($Object)
    {
        $Output = '';
        for ($a=0; $a<20; $a++) $Output.=mt_rand(0,9);
            return $Output;
    }