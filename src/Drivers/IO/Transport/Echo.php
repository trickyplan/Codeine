<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.x
     * @date 27.28.11
     * @time 6:28
     */

    setFn ('Open', function ($Call)
        {
            return true;
        });

    setFn ('Send', function ($Call)
        {
            echo '<div><pre>'.$Call['Message'];
            var_dump($Call['Call']);
            echo '</pre></div>';

            return true;
        });
