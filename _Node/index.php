<?php

    define('Root', dirname(__FILE__) . '/');
    // define('Root', __DIR__ . '/'); PHP 5.3
    
    if (isset ($_ENV['Codeine']))
        include $_ENV['Codeine'].'/Router.php';
    else
        if (file_exists('/var/lib/Codeine/Router.php'))
            include '/var/lib/Codeine/Router.php';
    else
        if ((include '../Codeine/Router.php') != true)
            die ('Codeine not found');

