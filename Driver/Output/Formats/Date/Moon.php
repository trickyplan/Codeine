<?php

    function F_Moon_Format ($Date)
    {
       $last_new_moon = mktime(7,14,0,6,15,2007);
       $t  = abs($Date-$last_new_moon);
       $tt = floor(abs($t/(29.5285*24*60*60)));
       $t  = $t-$tt*29.5285*24*60*60;
       $t  = $t/(24*60*60);
       $r  = ceil($t*28/29.5285);

       return $r;
    }
    
    