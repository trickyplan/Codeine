<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'][] = '<pre>'.shell_exec('uname -a').'</pre>'; // TODO
        return $Call;
    });

    setFn('Benchmark', function ($Call)
    {
        return F::Run('Server.Benchmark', 'Do', $Call);
    });