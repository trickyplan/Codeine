<?php

    include 'Core.php';

    Core::Initialize();

    try
    {
        if (!defined('Root'))
            define('Root', __DIR__);

        Code::Hook('Front', 'beforeStart');

        Profiler::MemFrom('Front');

        Code::Run(
                array(
                    array('F' => 'System/Interface/Input'),// Return Autorunned Call
                    array('F' => 'View/Render/Do'),
                    array('F' => 'System/Output/Output','D' => 'HTTP')
                    ), false, 'Chain');

        Profiler::MemTo('Front');

        Code::Hook('Front', 'afterStart');
    }
    catch (Exception $e)
    {
        // FIXME Error.json
        echo $e->getMessage();
    }