<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Make', function ($Call)
     {
         return '<div class="Example">'.highlight_string ($Call['Value'], true). '<samp>' . $Call['Output'] . '</samp></div>';
     });