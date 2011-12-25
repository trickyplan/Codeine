<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Make', function ($Call)
     {
         $Layout = F::Run(array(
                                  'Data' => array('Load', 'Layout'),
                                  'ID' => 'UI/Widgets/Form/Textarea.html'
                              ));

         if (preg_match_all('@<k>(.*)</k>@SsUu', $Layout, $Pockets))
         {
             foreach ($Pockets[1] as $IX => $Match)
             {
                 if(isset($Call[$Match]))
                 {
                     if (is_array($Call[$Match]))
                         $Call[$Match] = implode(' ', $Call[$Match]);
                     
                     $Layout = str_replace($Pockets[0][$IX], $Call[$Match], $Layout);
                 }
                 else
                     $Layout = str_replace($Pockets[0][$IX], '', $Layout);
             }
         }

         return $Layout;
     });