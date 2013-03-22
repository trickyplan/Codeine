<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.x
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $Ix => $Match)
          {
              $Match = json_decode(json_encode(simplexml_load_string('<exec>'.$Match.'</exec>')), true); // I love PHP :(

              if($Match)
              {
                  $Application = F::Run('Code.Flow.Application', 'Run', ['Run' => $Match, 'Context' => 'app', 'Session' => $Call['Session']]);
                  $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Application['Output'], $Call['Output']);
              }
              else
                  $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '', $Call['Output']);


          }

          return $Call;
     });