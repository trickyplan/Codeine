<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         $Element = F::Run('Entity', 'Read', array('Entity' => $Call['Link'], 'Where' => $Call['Value']));
         return $Element[0][$Call['Key']];
     });