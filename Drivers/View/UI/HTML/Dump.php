<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Make', function ($Call)
     {
         return '<pre>'.print_r($Call['Value'], true).'</pre>';
     });