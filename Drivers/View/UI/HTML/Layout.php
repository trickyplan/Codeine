<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Make', function ($Call)
     {
         $Layout = F::Run(array(
                             'Data' => array('Load', 'Layout'),
                             'ID' => $Call['ID'].'.html'
                         ));

         return $Layout;
     });