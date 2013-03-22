<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Project']['MTime'] = filemtime(Root.'/Options/Version.json');
        return $Call;
    });

    setFn('Down', function ($Call)
    {
        $DownFile = Root.'/locks/down';

        if (file_exists($DownFile))
        {
            if (unlink($DownFile))
                $Call = F::Hook('Enable.Success', $Call);
            else
                $Call = F::Hook('Enable.Fail', $Call);
        }
        else
        {
            if (touch($DownFile))
                $Call = F::Hook('Disable.Success', $Call);
            else
                $Call = F::Hook('Disable.Fail', $Call);
        }

        return $Call;
    });