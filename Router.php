<?php

    include 'Core.php';

    if (isset(Core::$Conf['Options']['Maintenance']) && Core::$Conf['Options']['Maintenance'] == true)
    {
        readfile(Root.'Layout/Site/Maintenance.html');
        die();
    }
    else
    {
        try
        {
            if (Server::Get('REQUEST_URI') == '/' or null === Server::Get('REQUEST_URI'))
                $URL = Core::$Conf['Options']['Start'];
            else
                $URL = Server::Get('REQUEST_URI');

            Application::Route($URL);

            Page::Output(Application::Run());
        }
        catch (Exception $e)
        {
            Data::Rollback();
            Log::Error($e->getMessage());
            Core::$Crash = true;
            if ($e->getCode()!= 0)
                Code::E('Error/Handlers/'.$e->getCode(),'Catch', $e);
        }
    }