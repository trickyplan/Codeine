<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Make', function ($Call)
     {
         return '<meta name="description" content="'.$Call['Value'].'" />';
     });