<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Make', function ($Call)
     {
         $Output = '<img src="'.$Call['Source'].'"'.(isset($Call['Class'])? ' class = "'.$Call['Class'].'"': '').' >';
// TODO Other attrs
         return $Output;
     });