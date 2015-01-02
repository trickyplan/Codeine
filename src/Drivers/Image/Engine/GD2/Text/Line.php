<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Add', function ($Call)
    {
        imagettftext (
            $Call['Image']['Object'],
            $Call['Size'],
            0,
            $Call['X'],
            $Call['Y'],
            imagecolorallocate($Call['Image']['Object'], $Call['Color']['R'], $Call['Color']['G'], $Call['Color']['B']),
            $Call['Font'],
            $Call['Text']);

        return $Call['Image'];
    });