<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Generate', function ($Call)
     {
         return sha1($_SERVER['HTTP_USER_AGENT']);
     });