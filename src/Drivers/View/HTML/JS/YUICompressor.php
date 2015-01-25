<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Command = 'yui-compressor --type js '.$Call['JS']['Cached Filename'].' -o '.$Call['JS']['Cached Filename'];

        F::Log('SH: '.$Command, LOG_INFO);
        F::Log(shell_exec($Command), LOG_INFO);
        return $Call;
    });