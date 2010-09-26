<?php

    include 'MicroCore.php';
    
    if (!defined('Root'))
        define('Root', __DIR__);
   
    try
    {
        Application::Route(Server::Arg('REQUEST_URI'));
        Page::Output(Application::Run());
    }
    catch (Exception $e)
    {
        Data::Rollback();
        
        Log::Error($e->getMessage());
        Core::$Crash = true;

        // FIXME Error.json
        if ($e->getCode()!= 0)
            Code::E('Error/Handlers/'.$e->getCode(),'Catch', $e);
        else
            echo $e->getMessage();
    }