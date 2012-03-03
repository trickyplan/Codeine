<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         $Layout = F::Run('View', 'Load', $Call, array ('ID' => $Call['Value']));

         while (preg_match_all ('@<k>(.*)</k>@SsUu', $Layout, $Pockets))
         {
             foreach ($Pockets[1] as $IX => $Match)
             {
                 if (($Value = F::Dot($Call['Data'], $Match)) !== null)
                 {
                     if (is_array ($Value))
                         $Value = implode (' ', $Call['Data'][$Match]);

                     $Layout = str_replace ($Pockets[0][$IX], $Value, $Layout);
                 }
                 else
                     $Layout = str_replace ($Pockets[0][$IX], '', $Layout);
             }
         }

         return $Layout;
    });