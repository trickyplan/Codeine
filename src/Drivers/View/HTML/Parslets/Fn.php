<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][1] as $Ix => $Match)
          {
              $Match = simplexml_load_string('<?xml version=\'1.0\'?><exec>'.$Match.'</exec>');
              $Match = json_decode(json_encode($Match), true);

              $Output = F::Live($Match);

              if (is_array($Output))
                  $Output = implode(',', $Output);

              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix],
                  $Output
              , $Call['Output']);
          }

          return $Call['Output'];
     });