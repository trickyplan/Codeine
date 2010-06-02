<?php

function F_PassUID_Generate($Prefix)
    {
        $Output = '';
        for ($a=0; $a<10; $a++) $Output.= mt_rand(0,9);
            return date('Y').$Output;
    }