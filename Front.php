<?php

    include 'MicroCore.php';

    Core::Initialize();

    try
    {
        if (!defined('Root'))
            define('Root', __DIR__);

        Code::Hook('Front', 'beforeStart');

        Profiler::MemFrom('Front');

        Code::Run(
            array(
                array('F' => 'System/Interface/Input'),
                array('F' => 'View/Render/Render','D' => 'Codeine'),
                array('F' => 'System/Output/Output','D' => 'HTTP')
                ), false, 'Chain');

        Profiler::MemTo('Front');

        Code::Hook('Front', 'afterStart');

        var_dump(Profiler::Output());
    }
    catch (Exception $e)
    {
        // FIXME Error.json
        echo $e->getMessage();
    }