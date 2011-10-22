<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Check', function ($Call)
     {
         $Result = shell_exec('export LANG=ru_RU.UTF-8 && echo "'.$Call['Value'].'" | /usr/sbin/cracklib-check');
         if (mb_strpos($Result, ':') !== false)
         {
             $Result = explode(':', $Result);
             return $Result[1];
         }
         else
             return $Result;

     });