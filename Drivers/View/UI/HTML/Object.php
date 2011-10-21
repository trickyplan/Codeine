<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Make', function ($Call)
     {
         // FIXME
         $Layout = F::Run(array(
              'Data' => array('Load', 'File'),
              'ID' => 'Layout/Entity/'.$Call['Entity'].'/'.$Call['Action'].'.'.$Call['Template'].'.html'
          ));
         
         $Output = array();


         $Object = $Call['Value'];

         $Object['Entity'] = $Call['Entity'];
         $Object['ID'] = $Call['ID'];

         $Output = $Layout;

         if (preg_match_all('@<k>(.*)</k>@SsUu', $Output, $Pockets))
         {
             foreach ($Pockets[1] as $IX => $Match)
                 if (isset($Object[$Match]))
                     $Output = str_replace($Pockets[0][$IX], $Object[$Match], $Output);
                 else
                     $Output = str_replace($Pockets[0][$IX], '', $Output);
         }

         return $Output;
     });