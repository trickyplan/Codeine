<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Simple Echo Logger
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 11.11.10
     * @time 4:13
     */

    $Initialize = function ($Call)
    {
        return true;
    };

    $Log = function ($Call)
    {
        echo '<div class="Log">'.$Call['Message'].'</div>';
    };

    $Info = function ($Call)
    {
        echo '<div class="Log Info">'.$Call['Message'].'</div>';
    };

    $Good = function ($Call)
    {
        echo '<div class="Log Good"> ☺'.$Call['Message'].'</div>';
    };

    $Bad = function ($Call)
    {
        echo '<div class="Log Bad"> ☹ '.$Call['Message'].'</div>';
    };

    $Warning = function ($Call)
    {
        echo '<div class="Log Warning">'.'Warning: '.$Call['Message'].'</div>';
    };

    $Error = function ($Call)
    {
        echo '<div class="Log Error">'.'Error: '.$Call['Message'].'</div>';
    };

    $Dump = function ($Call)
    {
        echo '<div class="Log Dump">'.'Dump: '.print_r($Call['Message'], true).'</div>';
    };

    $Hint = function ($Call)
    {
        echo '<div class="Log Hint">'.'Hint: '.$Call['Message'].'</div>';
    };

    $Important = function ($Call)
    {
        echo '<div class="Log Important">'.'<strong>'.$Call['Message'].'</strong></div>';
    };




