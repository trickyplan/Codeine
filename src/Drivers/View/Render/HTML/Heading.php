<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

     self::setFn('Make', function ($Call)
     {
         if (isset($Call['Localized']) && $Call['Localized'])
             $Call['Value'] = '<l>'. $Call['Value'].'</l>';

         $Output = '<h'.$Call['Level'].'>'.$Call['Value'].'</h'.$Call['Level'].'>';
         if (isset($Call['Subtext']))
             $Output.= '<div class="subText">'.$Call['Subtext'].'</div>';

         return $Output;
     });