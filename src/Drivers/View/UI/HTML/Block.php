<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Make', function ($Call)
     {
         if (is_array($Call['Class']))
             $Call['Class'] = implode(' ', $Call['Class']);
         
         return '<div class="'.$Call['Class'].'">'.$Call['Value'].'</div>';
     });