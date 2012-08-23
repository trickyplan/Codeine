<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

     self::setFn('Make', function ($Call)
     {
         return '<img src="/uploads/'.$Call['Value'].'" />';
     });