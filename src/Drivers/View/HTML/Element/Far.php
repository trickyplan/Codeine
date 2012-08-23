<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

     self::setFn('Make', function ($Call)
     {
         $Element = F::Run('Entity', 'Read', array('Entity' => $Call['Node']['Link']['Entity'], 'Where' => $Call['Value']));

         return F::Dot($Element[0],$Call['Node']['Link']['Key']);
     });