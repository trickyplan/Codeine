<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Command = 'pngcrush -rem alla -rem text -brute '.$Call['Image']['Cached Filename'].' '.$Call['Image']['Cached Filename'].'.crushed';
        F::Log('SH: '.$Command, LOG_INFO);
        F::Log(shell_exec($Command), LOG_INFO);
        rename($Call['Image']['Cached Filename'].'.crushed', $Call['Image']['Cached Filename']);

        return $Call;
    });