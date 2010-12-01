<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Simple Error Line
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 24.11.10
     * @time 3:27
     */

    self::Fn('Catch', function ($Call)
    {
        echo '<div class="Panel txtR"><small>'.$Call['Data']['Event'].'</small></div>';
    });