<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

     self::setFn('Make', function ($Call)
     {
         return '<code>' . $Call['Value'] . '</code>';
     });