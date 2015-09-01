<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Command = 'jpegtran  -optimize '.$Call['Image']['Cached Filename'].'> '.$Call['Image']['Cached Filename'];
        F::Log($Command, LOG_INFO);
        F::Log(shell_exec($Command), LOG_INFO);

        return $Call;
    });