<?php

    function F_Dev_Random ($Args)
    {
        $handle = fopen('/dev/urandom', "r");
        $contents = fread($handle, ($Args['Max']-$Args['Min']));
        fclose($handle);
        return round($Args['Min'] + (ord($contents)/256)*($Args['Max']-$Args['Min']));
    }