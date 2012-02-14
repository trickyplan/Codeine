<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Make', function ($Call)
     {
         return '<code>' . $Call['Value'] . '</code>';
     });