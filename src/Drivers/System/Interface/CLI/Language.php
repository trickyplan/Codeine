<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Get', function ($Call)
    {
        list ($Language, ) = explode('_', setlocale(LC_ALL, null));
        return $Language;
    });