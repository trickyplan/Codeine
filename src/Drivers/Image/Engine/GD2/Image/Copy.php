<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        imagecopy(
                $Call['Image']['Object'],
                $Call['Second Image']['Object'],
                $Call['X'],
                $Call['Y'],
                $Call['X2'],
                $Call['Y2'],
                $Call['Width'],
                $Call['Height']);

        return $Call['Image'];
    });