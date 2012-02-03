<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.0
     */

     self::setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][1] as $Ix => $Match)
          {
              $Match = json_decode ($Match, true);

              $Application = F::Run('Code.Flow.Application', 'Run', array('Value' => $Match));

              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Application['Output'], $Call['Output']);
          }

          return $Call['Output'];
     });