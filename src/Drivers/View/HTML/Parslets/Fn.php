<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 7.x
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $IX => $Match)
          {
             $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

              if ($Root->attributes()->type !== null)
                  $Type = (string) $Root->attributes()->type;
              else
                  $Type = $Call['Type'];

              $Match = F::Run('Formats.'.$Type, 'Decode', ['Value' => trim($Call['Parsed'][2][$IX])]);

              foreach ($Call['Inherited'] as $Key)
                  if (isset($Call[$Key]))
                    $Match['Call'][$Key] = $Call[$Key];


              $Output = F::Live($Match);

              if (is_array($Output))
                  $Output = '{}';

              if (is_float($Output))
                  $Output = str_replace(',', '.', $Output);

              $Call['Output'] = str_replace($Call['Parsed'][0][$IX],
                  $Output
              , $Call['Output']);
          }

          return $Call;
     });