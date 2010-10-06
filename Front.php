<?php

    include 'MicroCore.php';
    
    if (!defined('Root'))
        define('Root', __DIR__);
   
    try
    {
        Code::Hook('Front', __CLASS__, 'beforeProcess');

        Application::Route(Server::Arg('REQUEST_URI'));
        View::Output(Application::Run());

        Code::Hook('Front', __CLASS__, 'afterProcess');
    }
    catch (Exception $e)
    {
        Data::Rollback();
        
        Log::Error($e->getMessage());
        Core::$Crash = true;

        // FIXME Error.json
        echo $e->getMessage();
    }