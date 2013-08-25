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

              list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match['Source']]);

              $Image = F::Run('IO', 'Execute',
                                           [
                                               'Execute' => 'Version',
                                               'Storage' => 'Image',
                                               'Scope'   => [strtr($Asset, '.', '/'), 'img'],
                                               'Where'   => $ID
                                           ]).'_'.strtr($Asset.'.'.$ID, '/','.');

              $Path = $Call['Image']['Pathname'].$Image;

              if (F::Run ('IO', 'Execute',
                            [
                                'Execute' => 'Exist',
                                'Storage' => 'Image Cache',
                                'Scope'   => [$Call['RHost'], 'img'],
                                'Where'   => $Image
                            ]))
              {
                  F::Log('Image '.$Image.' cached', LOG_GOOD);
              }
              else
              {
                  F::Log('Image '.$Image.' missed', LOG_BAD);

                  $ImageData = F::Run('IO', 'Read',
                                           [
                                           'Storage' => 'Image',
                                           'Scope'   => [strtr($Asset, '.', '/'), 'img'],
                                           'Where'   => $ID
                                           ]);

                  if ($ImageData != null)
                  {
                      if (F::Run ('IO', 'Write',
                              [
                              'Storage' => 'Image Cache',
                              'Scope'   => [$Call['RHost'], 'img'],
                              'Where'   => $Image,
                              'Data' => $ImageData
                              ]
                      ))
                          F::Log('Image '.$Image.' writed', LOG_GOOD);
                      else
                          F::Log('Image '.$Image.' not writed', LOG_BAD);;
                  }
                  else
                  {
                      if (isset($Match['Default']))
                          list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match['Default']]);
                      else
                      {
                          $Asset = 'Default';
                          $ID = 'default.png';
                      }

                      if (F::Run ('IO', 'Write',
                          [
                              'Storage' => 'Image Cache',
                              'Scope'   => [$Call['RHost'], 'img'],
                              'Where'   => $Image,
                              'Data' => F::Run('IO', 'Read',
                                  [
                                      'Storage' => 'Image',
                                      'One' => true,
                                      'Scope'   => [$Asset, 'img'],
                                      'Where'   => $ID
                                  ])
                          ]
                      ))
                          F::Log('Default image '.$Image.' writed', LOG_GOOD);
                      else
                          F::Log('Default image '.$Image.' not writed', LOG_BAD);
                  }
              }

              $HTML = '<img src="'.$Path.'" '
                  .(isset($Match['Class']) ? ' class="'.$Match['Class'].'"': '')
                  .(isset($Match['Width']) ? ' width="'.$Match['Width'].'"': '')
                  .(isset($Match['Height']) ? ' height="'.$Match['Height'].'"': '')
                  .(isset($Match['Alt']) ? ' alt="'.$Match['Alt'].'"': '')
                  .' />';


              $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], $HTML, $Call['Output']);
          }


          return $Call;
     });