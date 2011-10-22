<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Generate', function ($Call)
     {
         $Contract = F::Options($Call['Value']['_N']);

         $Widgets = array(array(
                           'Place' => 'Content',
                           'Type'  => 'Heading',
                           'Level' => 1,
                           'Value' => $Call['Value']['_N']
                   ));

         return $Widgets;
     });