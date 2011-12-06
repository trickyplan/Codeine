<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][1] as $Ix => $Match)
          {
              $Output = F::Run($Call, json_decode($Match, true));

              if (is_array($Output))
              {
                  $Output = F::Run($Output,
                      array(
                           '_N' => 'View.Render.Pipeline',
                           '_F' => 'Process',
                           'Layout' => '<place>Content</place>'
                      )
                  );
              }

              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Output['Output'], $Call['Output']);
          }

          return $Call['Output'];
     });