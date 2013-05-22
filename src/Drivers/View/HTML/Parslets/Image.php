<?php

    /* Codeine
     * @author BreathLess
     * @description Image Parslet
     * @package Codeine
     * @version 7.x
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $Ix => $Match)
          {
              $Match = json_decode(json_encode(simplexml_load_string('<image>'.$Match.'</image>')), true); // I love PHP :(

              list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $Match['Source']));

              $Image = strtr($Asset.'.'.$ID, '/', '.');

              $Path = $Call['Image Cache'].$Image;

              if (F::Run ('IO', 'Exist',
                            [
                                'Storage' => 'Image Cache',
                                'Where'   => $Image
                            ]))
              {
                  $HTML = '<img src="'.$Path.'" '
                        .(isset($Match['Class']) ? ' class="'.$Match['Class'].'"': '')
                        .(isset($Match['Width']) ? ' width="'.$Match['Width'].'"': '')
                        .(isset($Match['Height']) ? ' height="'.$Match['Height'].'"': '')
                        .(isset($Match['Alt']) ? ' alt="'.$Match['Alt'].'"': '')
                      .' />';
              }
              else
              {
                  $ImageData = F::Run('IO', 'Read',
                                           [
                                           'Storage' => 'Image',
                                           'Scope'   => $Asset . '/img',
                                           'Where'   => $ID
                                           ]);

                  if ($ImageData != null)
                  {
                      F::Run ('IO', 'Write',
                              [
                              'Storage' => 'Image Cache',
                              'Where'   => $Image,
                              'Data' => $ImageData
                              ]
                      );

                      $HTML = '<img src="'.$Path.'" '
                        .(isset($Match['Class']) ? ' class="'.$Match['Class'].'"': '')
                        .(isset($Match['Width']) ? ' width="'.$Match['Width'].'"': '')
                        .(isset($Match['Height']) ? ' height="'.$Match['Height'].'"': '')
                        .(isset($Match['Alt']) ? ' alt="'.$Match['Alt'].'"': '')
                      .' />';
                  }
                  else
                      $HTML = '<img src="/default.gif" '
                        .(isset($Match['Class']) ? ' class="'.$Match['Class'].'"': '')
                        .(isset($Match['Width']) ? ' width="'.$Match['Width'].'"': '')
                        .(isset($Match['Height']) ? ' height="'.$Match['Height'].'"': '')
                        .(isset($Match['Alt']) ? ' alt="'.$Match['Alt'].'"': '')
                      .' />';
              }


              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $HTML, $Call['Output']);
          }


          return $Call;
     });