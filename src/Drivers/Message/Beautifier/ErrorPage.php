<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 27.08.11
     * @time 6:30
     */

    self::setFn('Format', function ($Call)
    {
        return '<div class="Error">'.$Call['Message'].'</div>';
    });
