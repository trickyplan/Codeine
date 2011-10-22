<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Make', function ($Call)
     {
         $Call['Widgets'][] =
                              array(
                                  'Place' => 'Content',
                                  'Type'  => 'Heading',
                                  'Level' => 3,
                                  'Value' => $Call['Element'].' ()'
                              );

         foreach ($Call['Value'] as $SectionName => $Fields)
         {
             $Call['Widgets'][] = array(
                              'Place' => 'Content',
                              'Type' => 'Heading',
                              'Level' => 4,
                              'Value' => '<l>'.$Call['_N'].'.'.$SectionName.'</l>'
                          );

             foreach ($Fields as $Field => $Keys)
             {
                 $Call['Widgets'][] = array(
                     'Place' => 'Content',
                     'Type' => 'Heading',
                     'Level' => 5,
                     'Value' => $Field
                 );

                 foreach ($Keys as $Key => $Value)
                     $Call['Widgets'][] = array(
                                          'Place' => 'Content',
                                          'Type' => 'Heading',
                                          'Level' => 6,
                                          'Value' => '<l>'.$Call['_N'].'.'.$Key.'</l> '.$Value
                                      );
             }
             
         }

         return $Call['Widgets'];
     });