<?php

    define('Root', __DIR__ . '/');
    
    if (isset ($_ENV['Codeine']))
        include $_ENV['Codeine'].'/Front.php';
    else
        if ((include '../Codeine/Front.php') != true)
            die ('Codeine not found');
