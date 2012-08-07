<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.6.2
     */

     self::setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $Ix => $Match)
          {
              $Match = json_decode(json_encode(simplexml_load_string('<exec>'.$Match.'</exec>')), true); // I love PHP :(

              $Application = F::Run('Code.Flow.Application', 'Run', array('Run' => $Match));

              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Application['Output'], $Call['Output']);
          }

          return $Call;
     });