<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Route', function ($Call)
     {
         $Entities = array('User', 'Person'); // FIXME!
         $Actions = array('Show', 'List', 'Create','Update', 'Delete'); // FIXME Too
         
         $NewCall = null;

         if (is_string($Call['Value']) && (mb_strpos($Call['Value'], '/') !== false))
         {
             $Slices = explode('/', mb_substr($Call['Value'], 1));
             $Size = count($Slices);

             if ($Size >= 2)
             {
                 if (in_array($Slices[0], $Actions) && in_array($Slices[1], $Entities))
                 {
                      $NewCall = array(
                          '_N' => 'Entity.'.$Slices[0],
                          '_F' => 'Do',
                          'Scope' => $Slices[1]
                      );

                      if ($Size == 3)
                        $NewCall['ID'] = $Slices[2];
                 }
             }
         }

         return $NewCall;
     });