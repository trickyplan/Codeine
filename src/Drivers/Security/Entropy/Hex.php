<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Get', function ($Call)
    {
        $Hex = '';
        for ($IX = 0; $IX < $Call['Size']; $IX++)
            $Hex.= dechex(rand(0, 15));

        return $Hex;
    });