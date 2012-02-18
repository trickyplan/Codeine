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
              $Match = simplexml_load_string('<?xml version=\'1.0\'?><exec>'.$Match.'</exec>');

              $Application = F::Run('Code.Flow.Application', 'Run', array('Value' => (array) $Match));

              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Application['Output'], $Call['Output']);
          }

          return $Call['Output'];
     });