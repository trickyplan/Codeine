<?php

    /* Codeine
     * @author BreathLess
     * @description Commandline LESS Compiler 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Compile', function ($Call)
    {
        return shell_exec('lessc ' . $Call['Value'].' > '.$Call['CSS']);
    });