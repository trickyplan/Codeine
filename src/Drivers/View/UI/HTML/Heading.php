<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Make', function ($Call)
     {
         $Output = '<h'.$Call['Level'].'>'.$Call['Value'].'</h'.$Call['Level'].'>';
         if (isset($Call['Subtext']))
             $Output.= '<div class="subText">'.$Call['Subtext'].'</div>';

         return $Output;
     });