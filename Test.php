<?php

    include __DIR__.'/MicroCore.php';

    echo 'Tests<pre>';
    $OK = '<span style="color: green;">OK!</span>';
    $Fail = '<span style="color: red;">Fail!</span>';

    echo 'Driver Call:         ';
    echo (Code::E('Test/Code', 'Function', array(), 'Driver') ? $OK:$Fail);
    echo '<br/>';

    echo 'SQL3D Connect:       ';
    echo (Data::Connect('SQL3D') ? $OK:$Fail);
    echo '<br/>';

