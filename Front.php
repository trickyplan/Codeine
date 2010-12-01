<?php

    include 'Core.php';

    try
    {
        defined('Root') || define('Root', __DIR__);
        
        defined('Environment') || define('Environment',
            (getenv('Codeine_Enviroment') ? getenv('Codeine_Enviroment') : 'Production'));

        Code::On('Front', 'beforeStart');

        Code::Run(
                array(
                    array('F' => 'System/Interface/Input'),// Return Autorunned Call
                    array('F' => 'View/Render/Do'),
                    array('F' => 'System/Output/Output','D' => 'HTTP')
                    ), Code::Normal, 'Chain');

        Code::On('Front', 'afterStart');
    }
    catch (Exception $e)
    {
        // FIXME Error.json
        echo $e->getMessage();
    }