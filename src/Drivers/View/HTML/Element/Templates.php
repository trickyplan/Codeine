<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         $MainLayout =  F::Run ('View', 'Load', $Call, array('ID' => $Call['Value']));

         if (preg_match_all ('@<k>(.*)</k>@SsUu', $MainLayout, $Pockets))
         {
             $Layout = array();

             foreach ($Call['Data'] as $N => $Data)
             {
                $Layout[$N] = $MainLayout;

                $Data['ID'] = $N;

                foreach ($Pockets[1] as $IX => $Match)
                {
                     if (isset($Data[$Match]))
                     {
                         if (is_array ($Data[$Match]))
                             $Data[$Match] = implode (' ', $Data[$Match]);

                         $Layout[$N] = str_replace ($Pockets[0][$IX], $Data[$Match], $Layout[$N]);
                     }
                     else
                         $Layout[$N] = str_replace ($Pockets[0][$IX], '', $Layout[$N]);
                }
             }

             return implode('', $Layout);
         }

         return '';
    });