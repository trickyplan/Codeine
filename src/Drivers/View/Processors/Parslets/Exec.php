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

              $Output = F::Run($Match['Service'], $Match['Method'], isset($Match['Call'])? $Match['Call']: null);

              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Output, $Call['Output']);
          }

          return $Call['Output'];
     });