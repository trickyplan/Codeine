<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         if (is_array($Call['Class']))
             $Call['Class'] = implode(' ', $Call['Class']);
         
         return '<div class="'.$Call['Class'].'">'.$Call['Value'].'</div>';
     });