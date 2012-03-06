<?php

    /* Codeine
     * @author BreathLess
     * @description Commandline LESS Compiler 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Compile', function ($Call)
    {
        return shell_exec('lessc ' . $Call['Value']);
    });