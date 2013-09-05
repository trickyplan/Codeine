<?php

    /* Codeine
     * @author BreathLess
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $Ix => $Match)
          {
              if (empty($Match))
                  $Match = '';
              else
              {
                  $Pathinfo = pathinfo($Match);
                  $Filesize = F::Run('Formats.Number.Filesize', 'Do',
                      ['Value' => filesize(Root.'/Public'.$Match)]); // FIXME

                  $Match = '
                  <image>
                    <Source>Formats/File:'.strtolower($Pathinfo['extension']).'.png</Source>
                    <Default>Formats/File:default.png</Default>
                  </image>
                  <a target="_blank" href="'. $Match.'">
                  '.$Pathinfo['basename'].' <small>('.$Filesize.')</a></small>';
              }


              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $Match, $Call['Output']);
          }

          return $Call;
     });