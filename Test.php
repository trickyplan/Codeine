<?php

    echo 'Tests<pre>';
    $OK = '<span style="color: green;">OK!</span>';
    $Fail = '<span style="color: red;">Fail!</span>';

    echo 'Driver Call:         ';
    $St = microtime(true);
    
    echo (Code::Test('{"N": "Calculate/Standart", "F":"SquareRoot"}')? $OK : $Fail).'<br/>';
    $Time = (microtime(true)-$St);
    echo round($Time*1000,2).' msec.<br/>'.round(1/$Time).' Hz';

