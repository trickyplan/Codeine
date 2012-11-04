<?php

    /* Codeine
     * @author BreathLess
     * @description Commandline LESS Compiler 
     * @package Codeine
     * @version 7.x
     */

    setFn('Compile', function ($Call)
    {
        return shell_exec('lessc ' . $Call['Value'].' > '.$Call['CSS']);
    });