<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $Ix => $Match)
          {
              $Match = json_decode(json_encode(simplexml_load_string('<exec>'.$Match.'</exec>')), true);

              $Application = F::Run('Code.Flow.Application', 'Run', array('Context' => 'app', 'Run' => F::Merge($Call, $Match)));

              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Application['Output'], $Call['Output']);
          }

          return $Call['Output'];
     });