<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('ToRedmine', function ($Call)
     {
         $Drivers = F::Run(
                 array(
                     '_N' => 'Code.Source.Enumerate.Driver',
                     '_F' => 'ListAll'
                 )
             );

         $Output = array();

         foreach ($Drivers as $Driver)
             if (null !== ($Fixmes = F::Run(
                          array(
                              '_N' => 'Code.Source.Detect.Fixme',
                              '_F' => 'Scan',
                              'Source' => file_get_contents(Codeine.'/Drivers'.$Driver.'.php')
                          )
                     )))
                 $Output[$Driver] = $Fixmes;

         var_dump($Output);
         // TODO Redmine Post
     });