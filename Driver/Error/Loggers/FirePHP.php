<?php

    function F_FirePHP_Initialize ($Args)
    {
        include Engine.Classes.'FirePHPCore/FirePHP.class.php';
        return FirePHP::getInstance(true);
    }

    function F_FirePHP_Info($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    }

    function F_FirePHP_Error($Args)
    {
        return $Args['Logger']->error($Args['Message']);
    }

    function F_FirePHP_Warning($Args)
    {
        return $Args['Logger']->warn($Args['Message']);
    }

    function F_FirePHP_Bad($Args)
    {
        return $Args['Logger']->warn($Args['Message']);
    }

    function F_FirePHP_Good ($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    }

    function F_FirePHP_Dump($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    }

    function F_FirePHP_Important($Args)
    {
        return $Args['Logger']->error($Args['Message']);
    }

    function F_FirePHP_Stage($Args)
    {
        $Args['Logger']->groupEnd($Args['Message']);
        $Args['Logger']->group($Args['Message']);
        return $Args['Logger']->info($Args['Message']);
    }

    function F_FirePHP_Hint($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    }

    function F_FirePHP_Perfomance($Args)
    {
        return $Args['Logger']->info($Args['Message']);
    }

    function F_FirePHP_Shutdown($Logger)
    {
        return true;
    }