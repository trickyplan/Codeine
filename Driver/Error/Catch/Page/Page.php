<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Simple Error Page
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 24.11.10
     * @time 3:27
     */

    self::Fn('Catch', function ($Call)
    {
        echo '<div class="Panel Panel_Error">
        <h3>Что-то где-то пошло не так</h4>
        <h4>'.$Call['Data']['Event'].'</h4>';
        var_dump($Call);
        echo '</div>';
    });