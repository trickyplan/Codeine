<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Command = 'jpegoptim '.$Call['Image']['Cached Filename'].' --strip-all';
        F::Log($Command, LOG_INFO);
        F::Log(shell_exec($Command), LOG_INFO);

        return $Call;
    });