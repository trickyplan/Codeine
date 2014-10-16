<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        F::Log(shell_exec('pngcrush '.$Call['Image']['Cached Filename'].' '.$Call['Image']['Cached Filename'].'.crushed'), LOG_INFO);
        rename($Call['Image']['Cached Filename'].'.crushed', $Call['Image']['Cached Filename']);

        return $Call;
    });