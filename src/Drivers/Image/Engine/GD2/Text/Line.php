<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Add', function ($Call)
    {
        imagestring($Call['Image']['Object'], $Call['Size'], $Call['X'], $Call['Y'], $Call['Text'],
            imagecolorallocate($Call['Image']['Object'], $Call['Color']['R'], $Call['Color']['G'], $Call['Color']['B']));

        return $Call['Image'];
    });