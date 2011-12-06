<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 27.08.11
     * @time 6:28
     */

    self::setFn ('Open', function ($Call)
        {

            return $Call;
        });

    self::setFn ('Send', function ($Call)
        {
            echo '<div><pre>'.$Call['Message'];
            var_dump($Call['Call']);
            echo '</pre></div>';

            return true;
        });
