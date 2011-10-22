<?php


    include 'Codeine.php';

    F::Bootstrap();

    $Call = array();

    list(, $Call['_N'], $Call['_F']) = $argv;
    if($argc > 2)
        for($i = 3; $i < $argc; $i+=2)
            if (isset ($argv[$i+1]))
                $Call[$argv[$i]] = $argv[$i+1];
            else
                $Call[$argv[$i]] = true;

    echo F::Run($Call);