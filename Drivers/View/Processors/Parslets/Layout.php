<?php

    /* Codeine
     * @author BreathLess
     * @description Layout Parslet 
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][1] as $Ix => $Match)
          {
              $Sublayout = F::Run(array(
                                           'Data' => array('Load', 'File'),
                                           'ID' => 'Layout/'.$Match.'.html'
                                       ));
              
              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Sublayout, $Call['Output']);
          }

          return $Call['Output'];
     });