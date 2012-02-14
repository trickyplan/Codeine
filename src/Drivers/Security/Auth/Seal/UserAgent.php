<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Generate', function ($Call)
     {
         return sha1($_SERVER['HTTP_USER_AGENT']);
     });