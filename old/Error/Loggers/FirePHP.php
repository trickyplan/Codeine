<?php

    $Initialize = function ($Args)
    {
        include Server::Locate('Package','FirePHPCore/FirePHP.class.php');
        return FirePHP::getInstance(true);
    };

    $Info = function ($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    };

    $Error = function ($Args)
    {
        return $Args['Logger']->error($Args['Message']);
    };

    $Warning = function ($Args)
    {
        return $Args['Logger']->warn($Args['Message']);
    };

    $Bad = function ($Args)
    {
        return $Args['Logger']->warn($Args['Message']);
    };

    $Good = function ($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    };

    $Dump = function ($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    };

    $Important = function ($Args)
    {
        return $Args['Logger']->error($Args['Message']);
    };

    $Stage = function ($Args)
    {
        $Args['Logger']->groupEnd($Args['Message']);
        $Args['Logger']->group($Args['Message']);
        return $Args['Logger']->info($Args['Message']);
    };

    $Hint = function ($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    };

    $Perfomance = function ($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    };

    $Shutdown = function ($Logger)
    {
        return true;
    };