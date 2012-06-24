<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

     self::setFn('Make', function ($Call)
     {
         $Output = '<ul'.(isset($Call['Class.List'])? ' class = "'.$Call['Class.List'].'"': '').'>';

         foreach($Call['Value'] as $Element)
             $Output.= '<li>'.$Element.'</li>';

         $Output .= '</ul>';

         return $Output;
     });