<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Command = 'yui-compressor --type css '.$Call['CSS']['Cached Filename'].' -o '.$Call['CSS']['Cached Filename'];

        F::Log('SH: '.$Command, LOG_INFO);
        F::Log(shell_exec($Command), LOG_INFO);
        return $Call;
    });