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

              if ($Match)
              {
                  foreach ($Call['Inherited'] as $Key)
                      if (isset($Call[$Key]))
                        $Match[$Key] = $Call[$Key];

                  $Application = F::Run('Code.Flow.Application', 'Run', ['Run' => $Match, 'Context' => 'app']);

                  if (isset($Application['Output']))
                    $Call['Output'] = str_replace(
                            $Call['Parsed'][0][$IX],
                            $Application['Output'],
                            $Call['Output']);
                  else
                    $Call['Output'] = str_replace(
                            $Call['Parsed'][0][$IX],
                            '',
                            $Call['Output']);

              }
              else
                  $Call['Output'] = str_replace($Call['Parsed'][0][$IX], '', $Call['Output']);


          }

          return $Call;
     });