<?php

    function F_File_Initialize($Args)
    {
        $LogFile = '/var/log/codeine/'.$Args.'.log';

        if (!file_exists($LogFile))
            touch($LogFile);

        return fopen($LogFile, 'a+');
    }

    function F_File_Info($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Error($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Warning($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Bad($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Good ($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Dump($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Important($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Stage($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Hint($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Perfomance($Args)
    {
        fwrite($Args['Logger'], $Args['Message']);
    }

    function F_File_Shutdown($Logger)
    {
        return fclose($Logger);
    }