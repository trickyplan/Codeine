<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         $Element = F::Run('Entity', 'Read', array('Entity' => $Call['Node']['Link']['Entity'], 'Where' => $Call['Value']));

         return $Element[0][$Call['Node']['Link']['Key']];
     });