<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Dot', function ($Call)
    {
        imagefill($Call['Image']['Object'], $Call['X'], $Call['Y'], imagecolorallocate($Call['Image']['Object'], $Call['Color']['R'], $Call['Color']['G'], $Call['Color']['B']));

        return $Call['Image'];
    });