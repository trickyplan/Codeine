<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Make', function ($Call)
     {

         $Layout =  F::Run ('Engine.Template', 'Load', array('Scope' => 'Static', 'ID' => $Call['Value']));

         if (preg_match_all ('@<k>(.*)</k>@SsUu', $Layout, $Pockets))
         {
             foreach ($Pockets[1] as $IX => $Match)
             {
                 if (isset($Call['Data'][$Match]))
                 {
                     if (is_array ($Call['Data'][$Match]))
                         $Call['Data'][$Match] = implode (' ', $Call['Data'][$Match]);

                     $Layout = str_replace ($Pockets[0][$IX], $Call['Data'][$Match], $Layout);
                 }
                 else
                     $Layout = str_replace ($Pockets[0][$IX], '', $Layout);
             }
         }

         return $Layout;
     });