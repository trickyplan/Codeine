<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed']['Value'] as $IX => $Match)
          {
              if (empty($Match))
                  $File = '';
              else
              {
                  if (F::file_exists($Filename = Root.'/Data/'.$Match))
                  {

                      $Pathinfo = pathinfo($Match);
                      $Filesize = F::Run('Formats.Number.Filesize', 'Do',
                          ['Value' => filesize($Filename)]); // FIXME

                      $Data = [
                              'URL' => $Match,
                              'Filename' => $Pathinfo['basename'],
                              'Filesize' => $Filesize,
                              'Extension' => $Pathinfo['extension']
                          ];

                      $File = F::Run('View', 'Load', $Call,
                      [
                          'Scope' => 'View/HTML/Parslets',
                          'ID' => 'File',
                          'Data' => $Data
                      ]);
                  }
                  else
                      $File = '';
              }


              $Replaces[$IX] = $File;
          }

          return $Replaces;
     });