<?php

    include 'Core.php';

    Core::Initialize();

    try
    {
        if (!defined('Root'))
            define('Root', __DIR__);

        Code::On('Front', 'beforeStart');

        Profiler::MemFrom('Front');

        Code::Run(
                array(
                    array('F' => 'System/Interface/Input'),// Return Autorunned Call
                    array('F' => 'View/Render/Do'),
                    array('F' => 'System/Output/Output','D' => 'HTTP')
                    ), Code::Normal, 'Chain');

        Profiler::MemTo('Front');

        Code::On('Front', 'afterStart');
    }
    catch (Exception $e)
    {
        // FIXME Error.json
        echo $e->getMessage();
    }